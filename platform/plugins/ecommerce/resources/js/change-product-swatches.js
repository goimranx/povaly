// 🚀 POVALY SWATCH SYSTEM v2.11.0 - Clean production build (no debug logs)

class ChangeProductSwatches {
    constructor() {
        this.xhr = null
        this.cache = new Map()
        this.cacheTimeout = 5 * 60 * 1000

        this.handleEvents()
    }

    handleEvents() {
        let _self = this
        let $body = $('body')

        // Hover preview for visual swatches - show image immediately without selecting
        // CRITICAL FIX v2.10.11: Simplified - just jump to image, no AJAX, no thumbnail reordering
        $body.on('mouseenter', '.product-attributes .visual-swatch label', function(e) {
            const $label = $(this)
            const $item = $label.closest('.bb-product-attribute-swatch-item')
            
            // Don't preview disabled items
            if ($item.hasClass('disabled')) {
                return
            }

            // Get all variation IDs for this color swatch (e.g., [184,185] for Pink 80cm and 100cm)
            const variationIdsStr = $item.data('variation-ids')
            let variationIds = []
            
            if (variationIdsStr) {
                try {
                    variationIds = typeof variationIdsStr === 'string' ? JSON.parse(variationIdsStr) : variationIdsStr
                } catch (e) {
                    // ignore parse errors
                }
            }
            
            // v2.10.38: Use the CURRENT thumbnail src (already updated by updateSwatchPricesSimple)
            // This ensures we get the correct image for the current Color + Size combination
            const $thumb = $item.find('.qs-var-item__thumb img')
            let swatchImageFull = $item.data('variation-image-full') // fallback
            
            if ($thumb.length && $thumb.attr('src')) {
                const thumbUrl = $thumb.attr('src')
                
                // Convert thumbnail URL to full-size URL
                // The thumbnail is already the correct one for current Color + Size combination
                // (updated by updateSwatchPricesSimple when size changes)
                if (window.siteSettings && window.siteSettings.mediaUrl) {
                    // Extract the relative path after the media URL base
                    const mediaBase = window.siteSettings.mediaUrl
                    const relativePath = thumbUrl.includes(mediaBase) 
                        ? thumbUrl.substring(thumbUrl.indexOf(mediaBase) + mediaBase.length)
                        : thumbUrl.split('/products/').pop() // fallback: extract after /products/
                    
                    // Remove -150x150 or -thumb suffix from filename
                    const fullPath = relativePath.replace(/-150x150(\.[^.]+)$/i, '$1')
                                                 .replace(/-thumb(\.[^.]+)$/i, '$1')
                    
                    swatchImageFull = mediaBase + fullPath
                } else {
                    // Fallback: simple regex replacement
                    swatchImageFull = thumbUrl.replace(/-150x150(\.[^.]+)$/i, '$1')
                                              .replace(/-thumb(\.[^.]+)$/i, '$1')
                }
            }
            
            if (swatchImageFull) {
                $item.data('is-hover-preview', true)
                _self.previewSwatchImage(swatchImageFull, $item)
            }
        })

        // When mouse leaves the swatch item, restore to selected variation
        $body.on('mouseleave', '.product-attributes .visual-swatch label', function(e) {
            const $label = $(this)
            const $item = $label.closest('.bb-product-attribute-swatch-item')
            
            // Clear hover preview flag
            $item.removeData('is-hover-preview')
            
            // Only restore if this item is not the selected one
            if (!$item.find('input[type=radio]').is(':checked')) {
                _self.restoreSelectedImage()
            }
        })

        $body.on('click', '.product-attributes .visual-swatch label, .product-attributes .text-swatch label', (e) => {
            e.preventDefault()

            let $this = $(e.currentTarget)
            let $radio = $this.find('input[type=radio]')
            let $item = $this.closest('.bb-product-attribute-swatch-item')
            let $wrapper = $this.closest('.bb-product-attribute-swatch')

            // Don't allow clicking disabled items
            if ($item.hasClass('disabled')) {
                return
            }

            if ($radio.is(':checked')) {
                return
            }

            // Remove selected class and checked attribute from all items in this group
            $wrapper.find('.bb-product-attribute-swatch-item').removeClass('selected')
            $wrapper.find('input[type=radio]').prop('checked', false).removeAttr('checked')

            // Add selected class and checked attribute to clicked item
            $item.addClass('selected')
            $radio.prop('checked', true).attr('checked', 'checked')

            // Update label
            const $label = $wrapper.find('.qs-swatch__selected-val')
            if ($label.length) {
                $label.text($item.data('title') || '')
            }

            $radio.trigger('change')
        })

        $body
            .off('change', '.product-attributes input, .product-attributes select')
            .on('change', '.product-attributes input, .product-attributes select', (event) => {
                const $element = $(event.currentTarget)
                const $parent = $element.closest('.product-attributes')

                // If it's a select dropdown, update the label
                if ($element.is('select')) {
                    const $wrapper = $element.closest('.bb-product-attribute-swatch')
                    const $selectedOption = $element.find('option:selected')
                    const $label = $wrapper.find('.qs-swatch__selected-val')
                    
                    if ($label.length && $selectedOption.val()) {
                        $label.text($selectedOption.data('title') || $selectedOption.text())
                    }
                }

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
        let _self = this

        let attributes = []
        let slugAttributes = {}

        /**
         * Break current request
         */
        if (_self.xhr) {
            _self.xhr.abort()
            _self.xhr = null
        }

        /**
         * Get attributes
         */
        let $attributeSwatches = $productAttributes.find('.attribute-swatches-wrapper')

        let referenceProduct = null
        $attributeSwatches.each((index, el) => {
            let $current = $(el)

            let $input
            if ($current.data('type') === 'dropdown') {
                $input = $current.find('select option:selected')
            } else {
                $input = $current.find('input[type=radio]:checked')
            }

            let slug = $input.data('slug')
            let value = $input.val()

            if (value) {
                let setSlug = $current.find('.attribute-swatch').data('slug')

                slugAttributes[setSlug] = slug
                attributes.push(value)

                referenceProduct = $input.data('reference-product')
            }
        })

        _self.callAjax(attributes, $productAttributes, slugAttributes, true, referenceProduct)
    }

    callAjax = function (attributes, $productAttributes, slugAttributes, updateUrl = true, referenceProduct = null) {
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
        
        // Check cache first
        const cached = _self.getFromCache(cacheKey)
        if (cached) {
            // Use cached response
            _self.handleResponse(cached, $productAttributes, slugAttributes, id, updateUrl)
            return
        }

        const ajaxUrl = $productAttributes.data('target')

        _self.xhr = $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: formData,
            beforeSend: () => {
                if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnBeforeChangeSwatches === 'function') {
                    window.EcommerceApp.defaultOnBeforeChangeSwatches(attributes, $productAttributes)
                }

                if (window.onBeforeChangeSwatches && typeof window.onBeforeChangeSwatches === 'function') {
                    window.onBeforeChangeSwatches(attributes, $productAttributes)
                }
            },
            success: (res) => {
                // Store in cache
                _self.setCache(cacheKey, res)
                
                // Handle response
                _self.handleResponse(res, $productAttributes, slugAttributes, id, updateUrl)
            },
            complete: (res) => {
                if (window.onChangeSwatchesComplete && typeof window.onChangeSwatchesComplete === 'function') {
                    window.onChangeSwatchesComplete(res, $productAttributes)
                }
            },
            error: (res) => {
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
        let _self = this

        if (window.EcommerceApp && typeof window.EcommerceApp.defaultOnChangeSwatchesSuccess === 'function') {
            window.EcommerceApp.defaultOnChangeSwatchesSuccess(res, $productAttributes)
        }

        if (window.onChangeSwatchesSuccess && typeof window.onChangeSwatchesSuccess === 'function') {
            window.onChangeSwatchesSuccess(res, $productAttributes)
        }

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

            // Update visual swatch thumbnails after variation response
            _self.updateVisualSwatchThumbnails(data, $productAttributes)
        }
        
        // Update swatch prices based on currently selected variation
        _self.updateSwatchPricesSimple(data, $productAttributes)
    }
    
    updateSwatchPricesSimple(variationData, $productAttributes) {
        const _self = this
        
        // Get ALL currently selected attribute IDs (from all sets)
        const selectedAttrIds = []
        $productAttributes.find('.bb-product-attribute-swatch-item.selected, .bb-product-attribute-swatch-item input:checked').each(function() {
            const $this = $(this)
            const $item = $this.hasClass('bb-product-attribute-swatch-item') ? $this : $this.closest('.bb-product-attribute-swatch-item')
            const attrId = parseInt($item.data('id'))
            if (attrId && !selectedAttrIds.includes(attrId)) {
                selectedAttrIds.push(attrId)
            }
        })
        
        // ALSO check for selected dropdowns
        $productAttributes.find('select.product-filter-item').each(function() {
            const $select = $(this)
            const selectedValue = parseInt($select.val())
            if (selectedValue && !selectedAttrIds.includes(selectedValue)) {
                selectedAttrIds.push(selectedValue)
            }
        })
        
        const ajaxUrl = $productAttributes.data('target')
        if (!ajaxUrl) {
            return
        }
        
        // Update each visual swatch price by fetching its variation data
        $productAttributes.find('.qs-var-item').each(function() {
            const $item = $(this)
            const colorId = parseInt($item.data('id'))
            const $priceDiv = $item.find('.qs-var-item__price')
            
            if (!$priceDiv.length || !colorId) return
            
            // Build test combination: this swatch's color + all OTHER selected attributes
            const $thisSet = $item.closest('.attribute-swatches-wrapper')
            const testAttrIds = selectedAttrIds.filter(id => {
                const $selectedItem = $productAttributes.find(`.bb-product-attribute-swatch-item[data-id="${id}"]`)
                const $selectedSet = $selectedItem.closest('.attribute-swatches-wrapper')
                return $selectedSet.data('slug') !== $thisSet.data('slug')
            })
            testAttrIds.push(colorId)
            
            // Fetch variation data for this combination
            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                data: {
                    attributes: testAttrIds,
                    _: +new Date()
                },
                success: (res) => {
                    if (res && res.data && !res.data.error_message) {
                        // Update price
                        const price = _self.formatPrice(Math.round(res.data.sale_price || res.data.price))
                        const origPrice = res.data.price > res.data.sale_price ? _self.formatPrice(Math.round(res.data.price)) : null
                        
                        let html = `<span class="qs-var-item__price-sale">${price}</span>`
                        if (origPrice) {
                            html += `<del class="qs-var-item__price-orig">${origPrice}</del>`
                        }
                        $priceDiv.html(html)
                        
                        // Update thumbnail - SAME LOGIC AS PRICE!
                        const $thumb = $item.find('.qs-var-item__thumb img')
                        if ($thumb.length && res.data.image_with_sizes && res.data.image_with_sizes.thumb) {
                            let thumbUrl = res.data.image_with_sizes.thumb
                            
                            // Handle case where thumb is an array of URLs - use first one
                            if (Array.isArray(thumbUrl)) {
                                thumbUrl = thumbUrl[0]
                            }
                            
                            if (thumbUrl) {
                                $thumb.attr('src', thumbUrl)
                            }
                        }
                    }
                }
            })
        })
    }    
    formatPrice(price) {
        const currencies = window.currencies || {}
        const symbol = currencies.symbol || '$'
        const isPrefix = currencies.is_prefix !== false
        const thousands = currencies.thousands_separator || ','
        const priceStr = Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousands)
        
        return isPrefix ? symbol + priceStr : priceStr + symbol
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

    previewVariation(variationId, $item) {
        $item.data('is-hover-preview', true)
        
        // Dispatch custom event for PV Gallery to handle
        const event = new CustomEvent('ecommerce.change-variation', {
            detail: {
                variationId: variationId,
                isPreview: true
            }
        })
        document.dispatchEvent(event)
    }

    previewSwatchImage(imageUrl, $item) {
        // Preview image in main gallery without changing selection
        const $product = $item.closest('.bb-product-detail')
        
        if (!$product.length) {
            return
        }
        
        // Determine if we're in Quick Shop or PV Gallery
        const $quickShopModal = $item.closest('#quick-shop-modal')
        const isQuickShop = $quickShopModal.length > 0
        
        if (isQuickShop) {
            // For Quick Shop Offcanvas Gallery
            if (typeof window.updateQSGalleryImages === 'function') {
                window.updateQSGalleryImages([imageUrl])
            }
        } else {
            // For PV Gallery - show the variation image on hover
            const $pvGallery = $product.find('.pv-gallery-main')
            
            if ($pvGallery.length) {
                const $mainImage = $pvGallery.find('#pv-main-image')
                
                if ($mainImage.length && imageUrl) {
                    // Store original src if not already stored
                    if (!$mainImage.data('original-src')) {
                        $mainImage.data('original-src', $mainImage.attr('src'))
                    }
                    
                    // v2.10.37: imageUrl is now the full-size URL from data-variation-image-full
                    // No need for regex replacement - just use it directly
                    $mainImage.attr('src', imageUrl)
                }
            }
        }
    }

    updateVisualSwatchThumbnails(variationData, $productAttributes) {
        if (!variationData) {
            return
        }

        // Get the variation image URL
        let thumbUrl = null
        if (variationData.image_with_sizes && variationData.image_with_sizes.thumb) {
            thumbUrl = variationData.image_with_sizes.thumb
        } else if (variationData.image) {
            thumbUrl = variationData.image
        }

        if (!thumbUrl) {
            return
        }

        // Find all visual swatches (those with thumbnails)
        let updatedCount = 0
        $productAttributes.find('.visual-swatch .qs-var-item').each(function() {
            const $item = $(this)
            const $thumb = $item.find('.qs-var-item__thumb')
            const $img = $thumb.find('img')
            const colorId = parseInt($item.data('id'))
            
            if (!$thumb.length || !colorId) {
                return // Skip items without thumbnails or IDs
            }

            // Check if this color attribute is part of the current selection
            const isInCurrentSelection = variationData.attributes && variationData.attributes.includes(colorId)
            
            if (isInCurrentSelection) {
                // This is the selected color - update its thumbnail
                if ($img.length) {
                    $img.attr('src', thumbUrl)
                } else {
                    // No img element, create one
                    $thumb.empty().append(
                        $('<img />').attr({
                            src: thumbUrl,
                            alt: $item.data('title') || '',
                            loading: 'lazy'
                        })
                    )
                }
                updatedCount++
            }
        })
    }

    restoreSelectedImage() {
        // Restore to currently selected variation's image
        const $selectedSwatch = $('.product-attributes .visual-swatch input:checked').closest('.bb-product-attribute-swatch-item')
        
        if (!$selectedSwatch.length) {
            return
        }
        
        const selectedImage = $selectedSwatch.data('variation-image')
        const $product = $selectedSwatch.closest('.bb-product-detail')
        
        if (!$product.length) {
            return
        }
        
        // Determine if we're in Quick Shop or PV Gallery
        const $quickShopModal = $selectedSwatch.closest('#quick-shop-modal')
        const isQuickShop = $quickShopModal.length > 0
        
        if (isQuickShop) {
            // For Quick Shop - restore to selected variation's first slide
            if (selectedImage && typeof window.updateQSGalleryImages === 'function') {
                window.updateQSGalleryImages([selectedImage])
            }
        } else {
            // For PV Gallery - use the gallery instance to jump to correct image
            if (window.pvGalleryInstance) {
                // Jump to the current active index (which is the selected variation's image)
                window.pvGalleryInstance.jumpToImage(window.pvGalleryInstance.currentIndex)
            } else {
                // Fallback if gallery instance not available
                const $pvGallery = $product.find('.pv-gallery-main')
                
                if ($pvGallery.length) {
                    const $mainImage = $pvGallery.find('#pv-main-image')
                    
                    if ($mainImage.length && selectedImage) {
                        // Strip size suffix to get full-size URL
                        let fullSizeUrl = selectedImage.replace(/-\d+x\d+(\.webp|\.jpg|\.jpeg|\.png)$/i, '$1');
                        fullSizeUrl = fullSizeUrl.replace(/-thumb(\.webp|\.jpg|\.jpeg|\.png)$/i, '$1');
                        $mainImage.attr('src', fullSizeUrl)
                    } else if ($mainImage.length && $mainImage.data('original-src')) {
                        // Restore original if no selection
                        $mainImage.attr('src', $mainImage.data('original-src'))
                        $mainImage.removeData('original-src')
                    }
                }
            }
        }
    }
}

$(() => {
    const swatchInstance = new ChangeProductSwatches()
    
    // Expose instance globally for theme callbacks
    window.swatchInstance = swatchInstance
    
    // Initialize swatch prices on page load
    $('.product-attribute-swatches').each(function() {
        const $container = $(this)
        
        // Update initial prices
        swatchInstance.updateSwatchPricesSimple(null, $container)
        
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