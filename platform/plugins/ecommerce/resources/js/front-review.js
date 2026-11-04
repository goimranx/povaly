const Review = {
    showError(message) {
        if (typeof Theme !== 'undefined') {
            Theme.showError(message)
        }
    },
    showSuccess(message) {
        if (typeof Theme !== 'undefined') {
            Theme.showSuccess(message)
        }
    },
    handleError(error, $form = null) {
        if (typeof Theme !== 'undefined') {
            Theme.handleError(error, $form)
        }
    },
    updateLazyLoad() {
        if (typeof Theme !== 'undefined' && Theme.lazyLoadInstance) {
            Theme.lazyLoadInstance.update()
        }
    },
    getCsrfToken($form = null) {
        if ($form && $form.length) {
            const formToken = $form.find('input[name="_token"]').val()
            if (formToken) {
                return formToken
            }
        }
        return $('meta[name="csrf-token"]').attr('content') || ''
    },
}

$(() => {
    const $reviewListContainer = $('.review-list-container')
    let imagesReviewBuffer = []

    const initLightGallery = (element) => {
        element.lightGallery({
            thumbnail: true,
        })
    }

    const getReviewList = (url, successCallback, additionalParams = {}) => {
        if (!url) {
            return
        }

        // Build URL with search and sort parameters
        const urlObj = new URL(url, window.location.origin)
        Object.keys(additionalParams).forEach(key => {
            if (additionalParams[key]) {
                urlObj.searchParams.set(key, additionalParams[key])
            }
        })

        $.ajax({
            url: urlObj.toString(),
            method: 'GET',
            beforeSend: () => {
                $reviewListContainer.append('<div class="loading-spinner"></div>')
            },
            success: ({ data, message }) => {
                $reviewListContainer.find('h4').text(message)
                $reviewListContainer.find('.review-list').html(data)

                Review.updateLazyLoad()

                initLightGallery($reviewListContainer.find('.review-images'))
                
                // Trigger event for other components to re-initialize
                $(document).trigger('reviewListUpdated')

                if (successCallback) {
                    successCallback()
                }
            },
            error: () => {
                // Silently handle error - page will be reloaded or user can refresh
            },
            complete: () => {
                $reviewListContainer.find('.loading-spinner').remove()
            },
        })
    }

    const getCurrentSearchParams = () => {
        return {
            search: $('.review-search-input').val() || '',
            sort_by: $('.review-sort-select').val() || 'newest',
            star: $('.review-star-filter').val() || ''
        }
    }

    // Update clear button visibility
    const updateClearButtonVisibility = () => {
        const hasSearch = $('.review-search-input').val().trim() !== ''
        const hasFilter = $('.review-star-filter').val() !== ''
        const hasSort = $('.review-sort-select').val() !== 'newest'

        if (hasSearch || hasFilter || hasSort) {
            $('.review-clear-btn').removeClass('d-none')
        } else {
            $('.review-clear-btn').addClass('d-none')
        }
    }

    const loadPreviewImage = function (input) {
        const $uploadText = $('.image-upload__text')
        const maxFiles = $(input).data('max-files')
        const filesAmount = input.files.length

        if (maxFiles) {
            if (filesAmount >= maxFiles) {
                $uploadText.closest('.image-upload__uploader-container').addClass('d-none')
            } else {
                $uploadText.closest('.image-upload__uploader-container').removeClass('d-none')
            }
            $uploadText.text(filesAmount + '/' + maxFiles)
        } else {
            $uploadText.text(filesAmount)
        }
        const viewerList = $('.image-viewer__list')
        const $template = $('#review-image-template').html()

        viewerList.find('.image-viewer__item').remove()

        if (filesAmount) {
            for (let i = filesAmount - 1; i >= 0; i--) {
                viewerList.prepend($template.replace('__id__', i))
            }
            for (let j = filesAmount - 1; j >= 0; j--) {
                let reader = new FileReader()
                reader.onload = function (event) {
                    viewerList
                        .find('.image-viewer__item[data-id=' + j + ']')
                        .find('img')
                        .attr('src', event.target.result)
                }
                reader.readAsDataURL(input.files[j])
            }
        }
    }

    const setImagesFormReview = function (input) {
        const dT = new ClipboardEvent('').clipboardData || new DataTransfer()

        for (let file of imagesReviewBuffer) {
            dT.items.add(file)
        }

        input.files = dT.files
        loadPreviewImage(input)
    }

    if ($reviewListContainer.length) {
        initLightGallery($('.review-images'))
        getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
    }

    $reviewListContainer.on('click', '.pagination a', (e) => {
        e.preventDefault()

        const url = $(e.currentTarget).prop('href')

        getReviewList(url, () => {
            $('html, body').animate({
                scrollTop: $reviewListContainer.offset().top - 130,
            })
        }, getCurrentSearchParams())
    })

    $(document).on('submit', '.product-review-container form, #reviewModal form', (e) => {
        e.preventDefault()
        e.stopPropagation()

        const $form = $(e.currentTarget)
        const $button = $form.find('button[type="submit"]')

        $.ajax({
            type: 'POST',
            cache: false,
            url: $form.prop('action'),
            data: new FormData($form[0]),
            contentType: false,
            processData: false,
            beforeSend: () => {
                $button.prop('disabled', true).addClass('loading')
            },
            success: ({ error, message }) => {
                if (!error) {
                    $form.find('select').val(0)
                    $form.find('textarea').val('')
                    $form.find('input[type=file]').val('')
                    $form.find('input.custom-field').val('')
                    imagesReviewBuffer = []

                    Review.showSuccess(message)

                    getReviewList($reviewListContainer.data('ajax-url'), () => {
                        if (!$('.review-list').length) {
                            setTimeout(() => window.location.reload(), 1000)
                        }
                    }, getCurrentSearchParams())
                } else {
                    Review.showError(message)
                }
            },
            error: (error) => {
                Review.handleError(error, $form)
            },
            complete: () => {
                $button.prop('disabled', false).removeClass('loading')
            },
        })
    })

    $(document).on('change', '.product-review-container form input[type=file], #reviewModal form input[type=file]', function (event) {
        event.preventDefault()

        const input = this
        const $input = $(input)
        const maxSize = $input.data('max-size')

        Object.keys(input.files).map(function (i) {
            if (maxSize && input.files[i].size / 1024 > maxSize) {
                const message = $input
                    .data('max-size-message')
                    .replace('__attribute__', input.files[i].name)
                    .replace('__max__', maxSize)
                Review.showError(message)
            } else {
                imagesReviewBuffer.push(input.files[i])
            }
        })

        const filesAmount = imagesReviewBuffer.length
        const maxFiles = $input.data('max-files')
        if (maxFiles && filesAmount > maxFiles) {
            imagesReviewBuffer.splice(filesAmount - maxFiles - 1, filesAmount - maxFiles)
        }

        setImagesFormReview(input)
    })

    $(document).on('click', '.product-review-container form .image-viewer__icon-remove, #reviewModal form .image-viewer__icon-remove', function (event) {
        event.preventDefault()
        const $this = $(event.currentTarget)
        let id = $this.closest('.image-viewer__item').data('id')
        imagesReviewBuffer.splice(id, 1)

        let input = $('.product-review-container form input[type=file], #reviewModal form input[type=file]')[0]
        setImagesFormReview(input)
    })

    $(document).on('click', '.delete-review-btn', function (e) {
        e.preventDefault()

        const $button = $(this)
        const reviewId = $button.data('review-id')
        const confirmMessage = $button.data('confirm-message')

        if (!confirm(confirmMessage)) {
            return
        }

        const deleteUrl = `/review/delete/${reviewId}`

        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': Review.getCsrfToken()
            },
            beforeSend: () => {
                $button.prop('disabled', true).addClass('loading')
            },
            success: ({ message }) => {
                Review.showSuccess(message)

                // Refresh review action section via AJAX
                const productId = $('.review-logged-in-wrapper').data('product-id')
                if (productId && typeof window.refreshReviewActionSection === 'function') {
                    window.refreshReviewActionSection(productId)
                }
                
                // Reload the review list using original function
                getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
            },
            error: (xhr) => {
                let message = 'An error occurred while deleting the review.'
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message
                }
                Review.showError(message)
            },
            complete: () => {
                $button.prop('disabled', false).removeClass('loading')
            }
        })
    })

    // Search functionality
    let searchTimeout
    $(document).on('input', '[data-bb-toggle="review-search"]', function() {
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(() => {
            updateClearButtonVisibility()
            if ($reviewListContainer.length) {
                getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
            }
        }, 500) // Debounce search for 500ms
    })

    // Sort functionality
    $(document).on('change', '[data-bb-toggle="review-sort"]', function() {
        updateClearButtonVisibility()
        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Star filter functionality
    $(document).on('change', '[data-bb-toggle="review-star-filter"]', function() {
        updateClearButtonVisibility()
        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Star filter progress bar click functionality
    $(document).on('click', '[data-bb-toggle="review-star-filter-bar"]', function() {
        const star = $(this).data('star')
        $('.review-star-filter').val(star)
        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Handle keyboard navigation for progress bars
    $(document).on('keydown', '[data-bb-toggle="review-star-filter-bar"]', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault()
            $(this).click()
        }
    })

    // Toggle search box
    $(document).on('click', '[data-bb-toggle="review-search-toggle"]', function() {
        const $container = $('.review-search-container')
        const $button = $(this)

        if ($container.hasClass('d-none')) {
            // Hide other containers
            $('.review-filter-container, .review-sort-container').addClass('d-none')
            $('.review-control-buttons .btn').removeClass('active')

            // Show search container
            $container.removeClass('d-none')
            $button.addClass('active')
            $('.review-search-input').focus()
        } else {
            $container.addClass('d-none')
            $button.removeClass('active')
        }
        updateClearButtonVisibility()
    })

    // Toggle filter dropdown
    $(document).on('click', '[data-bb-toggle="review-filter-toggle"]', function() {
        const $container = $('.review-filter-container')
        const $button = $(this)

        if ($container.hasClass('d-none')) {
            // Hide other containers
            $('.review-search-container, .review-sort-container').addClass('d-none')
            $('.review-control-buttons .btn').removeClass('active')

            // Show filter container
            $container.removeClass('d-none')
            $button.addClass('active')
        } else {
            $container.addClass('d-none')
            $button.removeClass('active')
        }
        updateClearButtonVisibility()
    })

    // Toggle sort dropdown
    $(document).on('click', '[data-bb-toggle="review-sort-toggle"]', function() {
        const $container = $('.review-sort-container')
        const $button = $(this)

        if ($container.hasClass('d-none')) {
            // Hide other containers
            $('.review-search-container, .review-filter-container').addClass('d-none')
            $('.review-control-buttons .btn').removeClass('active')

            // Show sort container
            $container.removeClass('d-none')
            $button.addClass('active')
        } else {
            $container.addClass('d-none')
            $button.removeClass('active')
        }
        updateClearButtonVisibility()
    })

    // Clear filters functionality
    $(document).on('click', '[data-bb-toggle="review-clear-filters"]', function() {
        $('.review-search-input').val('')
        $('.review-star-filter').val('')
        $('.review-sort-select').val('newest')

        // Hide all containers and remove active states
        $('.review-search-container, .review-filter-container, .review-sort-container').addClass('d-none')
        $('.review-control-buttons .btn').removeClass('active')

        updateClearButtonVisibility()

        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Vendor reply toggle
    $(document).on('click', '.vendor-reply-toggle-btn', function() {
        const reviewId = $(this).data('review-id')
        const $form = $(`.vendor-reply-form[data-review-id="${reviewId}"]`)
        const $button = $(this)

        $button.addClass('d-none')
        $form.removeClass('d-none')
        $form.find('textarea').focus()
    })

    // Vendor reply cancel
    $(document).on('click', '.vendor-reply-cancel-btn', function() {
        const reviewId = $(this).data('review-id')
        const $form = $(`.vendor-reply-form[data-review-id="${reviewId}"]`)
        const $button = $(`.vendor-reply-toggle-btn[data-review-id="${reviewId}"]`)

        $form.addClass('d-none')
        $form.find('textarea').val('')
        $button.removeClass('d-none')
    })

    // Vendor reply submit
    $(document).on('click', '.vendor-reply-submit-btn', function(e) {
        e.preventDefault()
        e.stopPropagation()

        const $button = $(this)
        const reviewId = $button.data('review-id')
        const $container = $(`.vendor-reply-form[data-review-id="${reviewId}"]`)
        const url = $container.data('url')
        const message = $container.find('.vendor-reply-message').val()

        if (!message || !message.trim()) {
            Review.showError('Please enter a reply message.')
            return false
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                message: message
            },
            headers: {
                'X-CSRF-TOKEN': Review.getCsrfToken()
            },
            beforeSend: () => {
                $button.prop('disabled', true).addClass('loading')
            },
            success: ({ error, message }) => {
                if (!error) {
                    Review.showSuccess(message)
                    // Reload page to show the new reply
                    setTimeout(() => {
                        window.location.reload()
                    }, 1000)
                } else {
                    Review.showError(message)
                    $button.prop('disabled', false).removeClass('loading')
                }
            },
            error: (xhr) => {
                let errorMessage = 'An error occurred while submitting the reply.'
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message
                }
                Review.showError(errorMessage)
                $button.prop('disabled', false).removeClass('loading')
            }
        })

        return false
    })

    // Vendor delete reply
    $(document).on('click', '.vendor-delete-reply-btn', function(e) {
        e.preventDefault()

        const $button = $(this)
        const url = $button.data('url')
        const confirmMessage = $button.data('confirm-message')

        if (!confirm(confirmMessage)) {
            return
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': Review.getCsrfToken()
            },
            beforeSend: () => {
                $button.prop('disabled', true)
            },
            success: ({ error, message }) => {
                if (!error) {
                    Review.showSuccess(message)
                    // Reload page to reflect the deleted reply
                    setTimeout(() => {
                        window.location.reload()
                    }, 1000)
                } else {
                    Review.showError(message)
                    $button.prop('disabled', false)
                }
            },
            error: (xhr) => {
                let errorMessage = 'An error occurred while deleting the reply.'
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message
                }
                Review.showError(errorMessage)
                $button.prop('disabled', false)
            }
        })
    })

    if (sessionStorage.reloadReviewsTab) {
        if ($('#product-detail-tabs a[href="#product-reviews"]').length) {
            new bootstrap.Tab($('#product-detail-tabs a[href="#product-reviews"]')[0]).show()
        }

        sessionStorage.reloadReviewsTab = false
    }
})

// Review Modal is handled by Bootstrap data attributes
// No additional JavaScript needed for toggle


// Star Rating Handler for Modal
$(() => {
    const updateStarRating = ($container) => {
        const $labels = $container.find('label')
        const $inputs = $container.find('input[type="radio"]')
        
        // Reset all stars to outline
        $labels.find('i').removeClass('ph-fill').addClass('ph')
        
        // Find checked input
        const $checked = $inputs.filter(':checked')
        if ($checked.length) {
            const checkedValue = parseInt($checked.val())
            // Fill stars up to the checked value
            $labels.each(function() {
                const starValue = parseInt($(this).attr('data-star-value'))
                if (starValue <= checkedValue) {
                    $(this).find('i').removeClass('ph').addClass('ph-fill')
                }
            })
        }
    }
    
    // Handle star rating clicks
    $(document).on('change', '.form-rating-stars input[type="radio"]', function() {
        const $container = $(this).closest('.form-rating-stars')
        updateStarRating($container)
    })
    
    // Handle star rating hover
    $(document).on('mouseenter', '.form-rating-stars label', function() {
        const $container = $(this).closest('.form-rating-stars')
        const $labels = $container.find('label')
        const hoverValue = parseInt($(this).attr('data-star-value'))
        
        $labels.each(function() {
            const starValue = parseInt($(this).attr('data-star-value'))
            const $icon = $(this).find('i')
            if (starValue <= hoverValue) {
                $icon.removeClass('ph').addClass('ph-fill')
            } else {
                $icon.removeClass('ph-fill').addClass('ph')
            }
        })
    })
    
    // Restore state on mouse leave
    $(document).on('mouseleave', '.form-rating-stars', function() {
        updateStarRating($(this))
    })
    
    // Initialize stars when modal opens
    $('#reviewModal').on('shown.bs.modal', function() {
        const $container = $(this).find('.form-rating-stars')
        if ($container.length) {
            updateStarRating($container)
        }
    })
})


// Review Comment Read More functionality
$(() => {
    const initReviewCommentTruncation = () => {
        $('.review-author__comment').each(function() {
            const $comment = $(this)
            const fullComment = $comment.data('full-comment') || $comment.text()
            
            // Check if content is truncated by checking scroll height
            if (this.scrollHeight > this.clientHeight) {
                // Add read more button if not already added
                if (!$comment.next('.btn-read-more').length) {
                    const $readMoreBtn = $('<button type="button" class="btn-read-more">Read more</button>')
                    $comment.after($readMoreBtn)
                }
            }
        })
    }

    // Handle read more button click
    $(document).on('click', '.btn-read-more', function(e) {
        e.preventDefault()
        const $button = $(this)
        const $comment = $button.prev('.review-author__comment')
        
        if ($comment.hasClass('expanded')) {
            $comment.removeClass('expanded')
            $button.text('Read more')
        } else {
            $comment.addClass('expanded')
            $button.text('Show less')
        }
    })

    // Initialize on page load
    initReviewCommentTruncation()

    // Re-initialize after AJAX load of reviews
    $(document).on('reviewListUpdated', function() {
        initReviewCommentTruncation()
    })
    
    // Also hook into existing review list loader if available
    if (typeof window.getReviewList !== 'undefined') {
        const originalGetReviewList = window.getReviewList
        window.getReviewList = function(...args) {
            const callback = args[1]
            args[1] = function() {
                if (callback) callback()
                initReviewCommentTruncation()
            }
            return originalGetReviewList.apply(this, args)
        }
    }
})


// Review Helpful and Share functionality
$(() => {
    // Cache for review helpful/share data
    const reviewDataCache = {
        helpful: {}, // reviewId: {user_marked, total_count, timestamp}
        shared: {}   // reviewId: {user_shared, timestamp}
    }

    // Initialize helpful buttons
    const initHelpfulButtons = () => {
        $(document).off('click', '.btn-helpful').on('click', '.btn-helpful', function(e) {
            e.preventDefault()
            e.stopPropagation()
            
            const $btn = $(this)
            const reviewId = $btn.data('review-id')
            const isHelpful = $btn.attr('data-helpful') === 'true'
            const $count = $btn.find('.helpful-count')
            const $icon = $btn.find('i')
            const currentCount = parseInt($count.text()) || 0
            
            // Optimistic UI update
            const newCount = isHelpful ? Math.max(0, currentCount - 1) : currentCount + 1
            $count.text(newCount)
            $btn.attr('data-helpful', !isHelpful)
            
            // Toggle icon between filled and outline
            if (!isHelpful) {
                $icon.removeClass('ph').addClass('ph-fill')
            } else {
                $icon.removeClass('ph-fill').addClass('ph')
            }
            
            // Update cache optimistically
            reviewDataCache.helpful[reviewId] = {
                user_marked: !isHelpful,
                total_count: newCount,
                timestamp: Date.now(),
                optimistic: true
            }
            
            // Send to server
            const apiCall = !isHelpful 
                ? $.ajax({
                    url: '/api/review-helpful',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': Review.getCsrfToken()
                    },
                    data: JSON.stringify({
                        review_id: reviewId,
                        action: 'mark'
                    }),
                    contentType: 'application/json'
                })
                : $.ajax({
                    url: `/api/review-helpful/${reviewId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': Review.getCsrfToken()
                    }
                })
            
            apiCall
                .done((data) => {
                    console.log('[Review] Helpful API success:', data)
                    
                    // Update cache with server truth
                    reviewDataCache.helpful[reviewId] = {
                        user_marked: data.user_marked !== undefined ? data.user_marked : !isHelpful,
                        total_count: data.total_count !== undefined ? data.total_count : newCount,
                        timestamp: Date.now(),
                        optimistic: false
                    }
                    
                    // Reconcile count if different
                    if (data.total_count !== undefined && data.total_count !== newCount) {
                        $count.text(data.total_count)
                    }
                })
                .fail((xhr) => {
                    // Log detailed error info
                    console.error('[Review] Helpful API failed:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        response: xhr.responseJSON || xhr.responseText,
                        reviewId: reviewId,
                        action: !isHelpful ? 'mark' : 'unmark'
                    })
                    
                    // Rollback on error
                    $count.text(currentCount)
                    $btn.attr('data-helpful', isHelpful)
                    
                    if (isHelpful) {
                        $icon.removeClass('ph').addClass('ph-fill')
                    } else {
                        $icon.removeClass('ph-fill').addClass('ph')
                    }
                    
                    reviewDataCache.helpful[reviewId] = {
                        user_marked: isHelpful,
                        total_count: currentCount,
                        timestamp: Date.now(),
                        optimistic: false
                    }
                })
        })
    }

    // Initialize share buttons
    const initShareButtons = () => {
        $(document).off('click', '.btn-share').on('click', '.btn-share', function(e) {
            e.preventDefault()
            e.stopPropagation()
            
            const $btn = $(this)
            const reviewId = $btn.data('review-id')
            const $icon = $btn.find('i')
            const isShared = $btn.attr('data-shared') === 'true'
            
            // Build review-specific URL with anchor
            const baseUrl = window.location.href.split('#')[0]
            const reviewUrl = baseUrl + '#review-' + reviewId
            
            // Optimistic UI update
            $btn.attr('data-shared', 'true')
            $icon.removeClass('ph').addClass('ph-fill')
            
            // Update cache
            reviewDataCache.shared[reviewId] = {
                user_shared: true,
                timestamp: Date.now()
            }
            
            // Track share function
            const trackShare = () => {
                return $.ajax({
                    url: '/api/review-share',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': Review.getCsrfToken()
                    },
                    data: JSON.stringify({
                        review_id: reviewId
                    }),
                    contentType: 'application/json'
                })
            }
            
            // Try native share API first
            if (navigator.share) {
                const shareData = {
                    title: 'Product Review',
                    text: 'Check out this review!',
                    url: reviewUrl
                }
                
                navigator.share(shareData)
                    .then(() => {
                        // User completed share, track it
                        trackShare()
                    })
                    .catch((err) => {
                        // User cancelled or error - rollback if not AbortError
                        if (err.name !== 'AbortError') {
                            $btn.attr('data-shared', isShared ? 'true' : 'false')
                            if (!isShared) {
                                $icon.removeClass('ph-fill').addClass('ph')
                            }
                        }
                    })
            } else {
                // Fallback to clipboard
                navigator.clipboard.writeText(reviewUrl)
                    .then(() => {
                        Review.showSuccess('Review link copied to clipboard!')
                        // Track the share
                        trackShare()
                    })
                    .catch(() => {
                        // Rollback on error
                        $btn.attr('data-shared', isShared ? 'true' : 'false')
                        if (!isShared) {
                            $icon.removeClass('ph-fill').addClass('ph')
                        }
                    })
            }
        })
    }

    // Load review helpful/share status
    const loadReviewStatus = ($reviewItem) => {
        const reviewId = $reviewItem.find('.btn-helpful').data('review-id')
        if (!reviewId) return
        
        // Check cache first - DISABLED: cache causes cross-user state pollution
        if (false && reviewDataCache.helpful[reviewId]) {
            const cachedData = reviewDataCache.helpful[reviewId]
            const $btn = $reviewItem.find('.btn-helpful')
            const $count = $btn.find('.helpful-count')
            const $icon = $btn.find('i')
            
            $count.text(cachedData.total_count)
            $btn.attr('data-helpful', cachedData.user_marked ? 'true' : 'false')
            
            if (cachedData.user_marked) {
                $icon.removeClass('ph').addClass('ph-fill')
            } else {
                $icon.removeClass('ph-fill').addClass('ph')
            }
        }
        
        // Fetch from server (silent fail if endpoint doesn't exist)
        $.ajax({
            url: `/api/review-helpful/${reviewId}`,
            method: 'GET'
        })
        .done((data) => {
            console.log('[Review] Load status success:', data)
            
            // Update cache
            reviewDataCache.helpful[reviewId] = {
                user_marked: data.user_marked || false,
                total_count: data.total_count || 0,
                timestamp: Date.now(),
                optimistic: false
            }
            
            // Update UI
            const $btn = $reviewItem.find('.btn-helpful')
            const $count = $btn.find('.helpful-count')
            const $icon = $btn.find('i')
            
            $count.text(data.total_count || 0)
            $btn.attr('data-helpful', data.user_marked ? 'true' : 'false')
            
            if (data.user_marked) {
                $icon.removeClass('ph').addClass('ph-fill')
            } else {
                $icon.removeClass('ph-fill').addClass('ph')
            }
        })
        .fail((xhr) => {
            // Log detailed error
            console.error('[Review] Load status failed:', {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON || xhr.responseText,
                reviewId: reviewId
            })
        })
    }

    // Initialize on page load
    initHelpfulButtons()
    initShareButtons()
    
    // Load status for all visible reviews
    $('.review-item').each(function() {
        loadReviewStatus($(this))
    })
    
    // Handle deep linking to specific review via anchor (#review-123)
    const handleReviewDeepLink = () => {
        const hash = window.location.hash
        if (hash && hash.startsWith('#review-')) {
            const reviewId = hash.replace('#review-', '')
            const $targetReview = $('#review-' + reviewId)
            
            if ($targetReview.length) {
                // Review found on current page - scroll to it
                setTimeout(() => {
                    $targetReview[0].scrollIntoView({ behavior: 'smooth', block: 'center' })
                    $targetReview.addClass('highlight-review')
                    setTimeout(() => $targetReview.removeClass('highlight-review'), 2000)
                }, 500)
            } else {
                // Review not on current page - might be on another pagination page
                // Try to load all reviews or search for it
                console.log('[Review] Review #' + reviewId + ' not found on current page')
                // You could add logic here to load the correct page if you have pagination data
            }
        }
    }
    
    // Call on page load
    handleReviewDeepLink()
    
    // Re-handle after AJAX updates
    $(window).on('hashchange', handleReviewDeepLink)
    
    // Re-initialize after AJAX review list load
    $(document).on('reviewListUpdated', function() {
        initHelpfulButtons()
        initShareButtons()
        
        // Load status for new reviews
        $('.review-item').each(function() {
            loadReviewStatus($(this))
        })
        
        // Re-check deep link in case review is now visible
        handleReviewDeepLink()
    })
    
    // Also hook into the existing review list loader if available
    if (typeof window.getReviewList !== 'undefined') {
        const originalGetReviewList = window.getReviewList
        window.getReviewList = function(...args) {
            const callback = args[1]
            args[1] = function() {
                if (callback) callback()
                
                // Re-initialize buttons
                initHelpfulButtons()
                initShareButtons()
                
                // Load status for new reviews
                $('.review-item').each(function() {
                    loadReviewStatus($(this))
                })
            }
            return originalGetReviewList.apply(this, args)
        }
    }
})
