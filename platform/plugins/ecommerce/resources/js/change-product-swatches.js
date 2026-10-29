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

        $body.on('click', '.product-attributes .visual-swatch label, .product-attributes .text-swatch label', (e) => {
            e.preventDefault()

            let $this = $(e.currentTarget)
            let $radio = $this.find('input[type=radio]')

            if ($radio.is(':checked')) {
                return
            }

            $radio.prop('checked', true)

            if ($this.closest('.visual-swatch').find('input[type=radio]:checked').length < 1) {
                $radio.prop('checked', true)
            }

            $radio.trigger('change')
        })

        $body
            .off('change', '.product-attributes input, .product-attributes select')
            .on('change', '.product-attributes input, .product-attributes select', (event) => {
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

        _self.xhr = $.ajax({
            url: $productAttributes.data('target'),
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

            // Auto-select first non-disabled option in wrappers that have no valid selection
            // This happens after server marks items as disabled/enabled
            const $wrappers = $productAttributes.find('.attribute-swatches-wrapper')
            
            $wrappers.each(function() {
                const $wrapper = $(this)
                const wrapperType = $wrapper.data('type')
                
                // Handle dropdowns
                if (wrapperType === 'dropdown') {
                    const $select = $wrapper.find('select')
                    const $selectedOption = $select.find('option:selected')
                    
                    // If selected option is disabled or nothing is selected
                    if (!$selectedOption.length || $selectedOption.is(':disabled')) {
                        // Find first enabled option
                        const $firstEnabled = $select.find('option:not(:disabled)').first()
                        if ($firstEnabled.length) {
                            $select.val($firstEnabled.val())
                        }
                    }
                    return
                }
                
                // Handle radio buttons (text/visual)
                const $checkedRadio = $wrapper.find('input[type=radio]:checked')
                const $checkedItem = $checkedRadio.closest('.bb-product-attribute-swatch-item')
                
                // If no radio is checked OR the checked one has .disabled class
                if (!$checkedRadio.length || $checkedItem.hasClass('disabled')) {
                    // Find first non-disabled item
                    const $items = $wrapper.find('.bb-product-attribute-swatch-item')
                    const $firstAvailable = $items.filter(function() {
                        return !$(this).hasClass('disabled')
                    }).first()
                    
                    if ($firstAvailable.length) {
                        const $radio = $firstAvailable.find('input[type=radio]')
                        
                        // Uncheck all radios in this wrapper
                        $wrapper.find('input[type=radio]').prop('checked', false)
                        
                        // Check the first available
                        $radio.prop('checked', true)
                        
                        // Update .selected class for styling
                        $items.removeClass('selected')
                        $firstAvailable.addClass('selected')
                    }
                }
            })

            // Visual hide/show for unavailable items
            _self.handleVisualItemsVisibility($productAttributes, data)

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

    /**
     * Show/hide visual items based on availability
     * Uses variation-prices data to determine which items are valid
     * with current selections from other (non-visual) attributes
     */
    handleVisualItemsVisibility = function($productAttributes, data) {
        // Get variation prices
        const variationPricesStr = $productAttributes.data('variation-prices')
        let variationPrices = []
        
        try {
            if (typeof variationPricesStr === 'string') {
                variationPrices = JSON.parse(variationPricesStr)
            } else if (Array.isArray(variationPricesStr)) {
                variationPrices = variationPricesStr
            }
        } catch (e) {
            return
        }

        if (variationPrices.length === 0) {
            return
        }

        // Get currently selected attribute IDs from NON-VISUAL attributes
        const otherSelections = []
        $productAttributes.find('.attribute-swatches-wrapper').each(function() {
            const $wrapper = $(this)
            const wrapperType = $wrapper.data('type')
            
            // Skip visual wrappers
            if (wrapperType === 'visual') {
                return
            }
            
            let selectedId = null
            if (wrapperType === 'dropdown') {
                const $selected = $wrapper.find('select option:selected')
                if ($selected.length && $selected.val()) {
                    selectedId = parseInt($selected.val())
                }
            } else {
                const $checked = $wrapper.find('input[type=radio]:checked')
                if ($checked.length && $checked.val()) {
                    selectedId = parseInt($checked.val())
                }
            }
            
            if (selectedId) {
                otherSelections.push(selectedId)
            }
        })

        // Find all visual attribute wrappers
        const $visualWrappers = $productAttributes.find('.attribute-swatches-wrapper[data-type="visual"]')
        
        $visualWrappers.each(function() {
            const $wrapper = $(this)
            const $items = $wrapper.find('.attribute-swatch')
            
            $items.each(function() {
                const $item = $(this)
                const $input = $item.find('input[type=radio]')
                const itemId = parseInt($input.val())
                
                if (!itemId) return
                
                // Check if this visual item has a valid combination with other selections
                let hasValidCombination = false
                
                if (otherSelections.length > 0) {
                    // Check if there's a variation containing THIS visual item + ALL other selections
                    hasValidCombination = variationPrices.some(variation => {
                        const attrs = variation.attrs || []
                        // Must include this visual item
                        if (!attrs.includes(itemId)) return false
                        // Must include ALL other selected attributes
                        return otherSelections.every(otherId => attrs.includes(otherId))
                    })
                } else {
                    // No other selections yet, show all visual items that exist in ANY variation
                    hasValidCombination = variationPrices.some(variation => {
                        return (variation.attrs || []).includes(itemId)
                    })
                }
                
                if (!hasValidCombination) {
                    // Hide this item
                    $item.addClass('disabled')
                    $item.css('display', 'none')
                    
                    // If it's selected, deselect it
                    if ($item.hasClass('selected') || $input.is(':checked')) {
                        $item.removeClass('selected')
                        $input.prop('checked', false)
                    }
                } else {
                    // Show this item
                    $item.removeClass('disabled')
                    $item.css('display', '')
                    $item.removeAttr('style')
                }
            })
            
            // After hiding, check if we need to auto-select first available
            const $availableItems = $wrapper.find('.attribute-swatch:not(.disabled)')
            const $checkedInput = $wrapper.find('input[type=radio]:checked')
            
            // If no item is checked or the checked item is hidden, auto-select first available
            if ($availableItems.length > 0 && !$checkedInput.length) {
                const $firstAvailable = $availableItems.first()
                const $firstInput = $firstAvailable.find('input[type=radio]')
                
                if ($firstInput.length) {
                    // Clear all selections
                    $wrapper.find('input[type=radio]').prop('checked', false)
                    $wrapper.find('.attribute-swatch').removeClass('selected')
                    
                    // Select first available
                    $firstInput.prop('checked', true)
                    $firstAvailable.addClass('selected')
                }
            }
        })
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
