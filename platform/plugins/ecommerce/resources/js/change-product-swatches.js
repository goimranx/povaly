class ChangeProductSwatches {
    constructor() {
        this.xhr = null
        this.cache = new Map() // Client-side cache for variation responses
        this.cacheTimeout = 5 * 60 * 1000 // 5 minutes cache

        this.handleEvents()
    }

    handleEvents() {
        let _self = this
        let $body = $('body')

        console.log('[VARIATION DEBUG] handleEvents() called - setting up listeners')

        $body.on('click', '.product-attributes .visual-swatch label, .product-attributes .text-swatch label', (e) => {
            e.preventDefault()
            console.log('[VARIATION DEBUG] Visual/Text swatch clicked')

            let $this = $(e.currentTarget)
            let $radio = $this.find('input[type=radio]')

            if ($radio.is(':checked')) {
                console.log('[VARIATION DEBUG] Radio already checked, returning')
                return
            }

            $radio.prop('checked', true)

            if ($this.closest('.visual-swatch').find('input[type=radio]:checked').length < 1) {
                $radio.prop('checked', true)
            }

            console.log('[VARIATION DEBUG] Triggering change event on radio')
            $radio.trigger('change')
        })

        // ORIGINAL event listener for all inputs/selects
        $body
            .off('change', '.product-attributes input, .product-attributes select')
            .on('change', '.product-attributes input, .product-attributes select', (event) => {
                console.log('[VARIATION DEBUG] Change event fired!', {
                    type: event.target.tagName,
                    value: event.target.value,
                    classes: event.target.className
                })
                alert('[VARIATION DEBUG] Change detected on: ' + event.target.tagName + ' (value: ' + event.target.value + ')')
                
                const $parent = $(event.currentTarget).closest('.product-attributes')

                _self.getProductVariation($parent)
            })
            
        // ADDITIONAL: Direct listener for dropdown specifically (in case event doesn't bubble)
        $body
            .off('change', '.product-attributes .product-filter-item')
            .on('change', '.product-attributes .product-filter-item', (event) => {
                console.log('[VARIATION DEBUG] DROPDOWN SPECIFIC - Change event fired!', {
                    type: event.target.tagName,
                    value: event.target.value,
                    classes: event.target.className
                })
                alert('[VARIATION DEBUG] DROPDOWN changed to: ' + event.target.value)
                
                const $parent = $(event.currentTarget).closest('.product-attributes')

                _self.getProductVariation($parent)
            })

        if ($('.product-attribute-swatches').length) {
            window.addEventListener(
                'popstate',
                function (e) {
                    if (e.state?.product_attributes_id) {
                        let $el = $('#' + e.state.product_attributes_id)

                        if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnChangeSwatchesSuccess === 'function') {
                            window.EcommerceApp.defaultOnChangeSwatchesSuccess(e.state.data, $el)
                        }

                        if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === 'function') {
                            window.onChangeSwatchesSuccess(e.state.data, $el)
                        }

                        if (e.state.slugAttributes) {
                            _self.updateSelectingAttributes(e.state.slugAttributes, $el)
                        }
                    } else {
                        $('.product-attribute-swatches').each(function (i, el) {
                            let params = _self.parseParamsSearch()
                            let attributes = []
                            let slugAttributes = {}
                            let $el = $(el)

                            if (params && Object.keys(params).length) {
                                $.each(params, function (key, slug) {
                                    let $parent = $el.find('.attribute-swatches-wrapper[data-slug="' + key + '"]')
                                    if ($parent.length) {
                                        let value
                                        if ($parent.data('type') === 'dropdown') {
                                            value = $parent.find('option[data-slug="' + slug + '"]').val()
                                        } else {
                                            value = $parent.find('input[data-slug="' + slug + '"]').val()
                                        }

                                        if (value) {
                                            attributes.push(value)
                                            slugAttributes[key] = value
                                        }
                                    }
                                })
                            }

                            _self.callAjax(attributes, $el, slugAttributes, false)
                        })
                    }
                },
                false
            )
        }
    }

    getProductVariation($productAttributes) {
        console.log('[VARIATION DEBUG] getProductVariation() called')
        alert('[VARIATION DEBUG] getProductVariation() started!')
        
        let _self = this

        let attributes = []
        let slugAttributes = {}

        /**
         * Break current request
         */
        if (_self.xhr) {
            console.log('[VARIATION DEBUG] Aborting previous XHR')
            _self.xhr.abort()

            _self.xhr = null
        }

        /**
         * Get attributes
         */
        let $attributeSwatches = $productAttributes.find('.attribute-swatches-wrapper')
        console.log('[VARIATION DEBUG] Found attribute wrappers:', $attributeSwatches.length)

        let referenceProduct = null
        $attributeSwatches.each((index, el) => {
            let $current = $(el)
            console.log('[VARIATION DEBUG] Processing attribute wrapper', index, {
                type: $current.data('type'),
                slug: $current.find('.attribute-swatch').data('slug')
            })

            let $input
            if ($current.data('type') === 'dropdown') {
                $input = $current.find('select option:selected')
                console.log('[VARIATION DEBUG] Dropdown found, selected value:', $input.val())
            } else {
                $input = $current.find('input[type=radio]:checked')
                console.log('[VARIATION DEBUG] Radio found, checked value:', $input.val())
            }

            let slug = $input.data('slug')
            let value = $input.val()
            
            console.log('[VARIATION DEBUG] Input data:', { slug, value })

            if (value) {
                let setSlug = $current.find('.attribute-swatch').data('slug')

                slugAttributes[setSlug] = slug
                attributes.push(value)

                referenceProduct = $input.data('reference-product')
                
                console.log('[VARIATION DEBUG] Added to attributes:', {
                    setSlug,
                    slug,
                    value,
                    referenceProduct
                })
            }
        })

        // DEBUG: Log what we're sending to server
        console.log('[VARIATION DEBUG] Final data to send:', {
            attributes: attributes,
            slugAttributes: slugAttributes,
            referenceProduct: referenceProduct
        })
        
        // Also show alert for visibility
        alert('DEBUG: Sending attributes: ' + JSON.stringify(attributes))

        _self.callAjax(attributes, $productAttributes, slugAttributes, true, referenceProduct)
    }

    callAjax = function (attributes, $productAttributes, slugAttributes, updateUrl = true, referenceProduct = null) {
        console.log('[VARIATION DEBUG] callAjax() called with:', {
            attributes,
            slugAttributes,
            updateUrl,
            referenceProduct
        })
        
        let _self = this
        let formData = {
            attributes,
            _: +new Date(),
        }

        if (referenceProduct) {
            formData.reference_product = referenceProduct
        }

        let id = $productAttributes.prop('id')
        
        // Create cache key from attributes and language
        const locale = document.documentElement.lang || 'en'
        const cacheKey = JSON.stringify(attributes.sort()) + (referenceProduct || '') + '_v1_' + locale
        
        console.log('[VARIATION DEBUG] Cache key:', cacheKey)
        
        // Check cache first
        const cached = _self.getFromCache(cacheKey)
        if (cached) {
            console.log('[VARIATION DEBUG] Using cached response')
            // Use cached response
            _self.handleResponse(cached, $productAttributes, slugAttributes, id, updateUrl)
            return
        }

        const ajaxUrl = $productAttributes.data('target')
        console.log('[VARIATION DEBUG] Making AJAX request to:', ajaxUrl)
        console.log('[VARIATION DEBUG] AJAX data:', formData)

        _self.xhr = $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: formData,
            beforeSend: () => {
                console.log('[VARIATION DEBUG] AJAX beforeSend')
                
                if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnBeforeChangeSwatches === 'function') {
                    window.EcommerceApp.defaultOnBeforeChangeSwatches(attributes, $productAttributes)
                }

                if (window.onBeforeChangeSwatches && typeof window.onBeforeChangeSwatches === 'function') {
                    window.onBeforeChangeSwatches(attributes, $productAttributes)
                }
            },
            success: (res) => {
                console.log('[VARIATION DEBUG] AJAX success! Response:', res)
                
                // Store in cache
                _self.setCache(cacheKey, res)
                
                // Handle response
                _self.handleResponse(res, $productAttributes, slugAttributes, id, updateUrl)
            },
            complete: (res) => {
                console.log('[VARIATION DEBUG] AJAX complete')
                
                if (window.onChangeSwatchesComplete && typeof window.onChangeSwatchesComplete === 'function') {
                    window.onChangeSwatchesComplete(res, $productAttributes)
                }
            },
            error: (res) => {
                console.error('[VARIATION DEBUG] AJAX error!', res)
                alert('[VARIATION DEBUG] AJAX ERROR: ' + (res.responseText || res.statusText))
                if (window.onChangeSwatchesError && typeof window.onChangeSwatchesError === 'function') {
                    window.onChangeSwatchesError(res, $productAttributes)
                }
            },
        })
    }

    updateSelectingAttributes = function (slugAttributes, $el) {
        $.each(slugAttributes, function (key, slug) {
            let $parent = $el.find('.attribute-swatches-wrapper[data-slug="' + key + '"]')

            if ($parent.length) {
                if ($parent.data('type') === 'dropdown') {
                    let selected = $parent.find('select option[data-slug="' + slug + '"]').val()
                    $parent.find('select').val(selected)
                } else {
                    $parent.find('input:checked').prop('checked', false)
                    $parent.find('input[data-slug=' + slug + ']').prop('checked', true)
                }
            }
        })
    }

    parseParamsSearch = function (query, includeArray = false) {
        let pairs = query || window.location.search.substring(1)
        let re = /([^&=]+)=?([^&]*)/g
        let decodeRE = /\+/g // Regex for replacing addition symbol with a space
        let decode = function (str) {
            return decodeURIComponent(str.replace(decodeRE, ' '))
        }
        let params = {},
            e
        while ((e = re.exec(pairs))) {
            let k = decode(e[1]),
                v = decode(e[2])
            if (k.substring(k.length - 2) === '[]') {
                if (includeArray) {
                    k = k.substring(0, k.length - 2)
                }
                ;(params[k] || (params[k] = [])).push(v)
            } else params[k] = v
        }
        return params
    }
    
    handleResponse = function(res, $productAttributes, slugAttributes, id, updateUrl) {
        console.log('[VARIATION DEBUG] handleResponse() called')
        console.log('[VARIATION DEBUG] Full response object:', res)
        
        let _self = this

        // DEBUG: Log what server returned
        console.log('[VARIATION DEBUG] Server response data:', {
            unavailable_attribute_ids: res.data?.unavailable_attribute_ids,
            selected_attributes: res.data?.selected_attributes,
            price: res.data?.price,
            error_message: res.data?.error_message,
            full_data: res.data
        })
        
        // Also show alert for visibility
        alert('DEBUG: Server returned unavailable IDs: ' + JSON.stringify(res.data?.unavailable_attribute_ids))
        
        console.log('[VARIATION DEBUG] Calling window callbacks...')

        if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnChangeSwatchesSuccess === 'function') {
            console.log('[VARIATION DEBUG] Calling EcommerceApp.defaultOnChangeSwatchesSuccess')
            window.EcommerceApp.defaultOnChangeSwatchesSuccess(res, $productAttributes)
        }

        if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === 'function') {
            console.log('[VARIATION DEBUG] Calling onChangeSwatchesSuccess')
            window.onChangeSwatchesSuccess(res, $productAttributes)
        }

        console.log('[VARIATION DEBUG] Window callbacks completed')

        const { data, message } = res

        if (data && !data.error_message) {
            if (data.selected_attributes) {
                slugAttributes = {}
                $.each(data.selected_attributes, (index, item) => {
                    slugAttributes[item.set_slug] = item.slug
                })
            }

            if (id) {
                _self.updateSelectingAttributes(slugAttributes, $('#' + id))
            }

            // Skip URL updates if data-update-url="false" is set on the container
            const shouldUpdateUrl = $productAttributes.data('update-url') !== false

            if (shouldUpdateUrl) {
                const url = new URL(window.location)

                $.each(slugAttributes, (name, value) => {
                    url.searchParams.set(name, value)
                })

                if (updateUrl && url != window.location.href) {
                    window.history.pushState(
                        { formData: { attributes: res.data.attributes }, data: res, product_attributes_id: id, slugAttributes },
                        message,
                        url
                    )
                } else {
                    window.history.replaceState(
                        { formData: { attributes: res.data.attributes }, data: res, product_attributes_id: id, slugAttributes },
                        message,
                        url
                    )
                }

                const $clipboardBtn = $('[data-bb-toggle="social-sharing-clipboard"]')
                if ($clipboardBtn.length) {
                    $clipboardBtn.attr('data-clipboard-text', url.href)
                }
            }
        }
    }
    
    getFromCache(key) {
        const item = this.cache.get(key)
        if (!item) return null
        
        // Check if cache is expired
        if (Date.now() - item.timestamp > this.cacheTimeout) {
            this.cache.delete(key)
            return null
        }
        
        return item.data
    }
    
    setCache(key, data) {
        this.cache.set(key, {
            data: data,
            timestamp: Date.now()
        })
        
        // Limit cache size to prevent memory issues
        if (this.cache.size > 100) {
            // Remove oldest entries
            const firstKey = this.cache.keys().next().value
            this.cache.delete(firstKey)
        }
    }
}

$(() => {
    const swatchInstance = new ChangeProductSwatches()
    
    // Check initial selection on page load
    $('.product-attribute-swatches').each(function() {
        const $container = $(this)
        const hasCheckedRadio = $container.find('input[type=radio]:checked:not(:disabled)').length > 0
        const hasSelectedDropdown = $container.find('select.product-filter-item').filter(function() {
            return !!$(this).val()
        }).length > 0

        // If no valid variation is selected, select the first available one
        if (!hasCheckedRadio && !hasSelectedDropdown) {
            $container.find('.attribute-swatches-wrapper').each(function() {
                const $wrapper = $(this)
                const $firstAvailable = $wrapper.find('input[type=radio]:not(:disabled)').first()

                if ($firstAvailable.length) {
                    $firstAvailable.prop('checked', true)
                }
            })

            // Trigger change to update product info
            swatchInstance.getProductVariation($container)
        }
    })
})