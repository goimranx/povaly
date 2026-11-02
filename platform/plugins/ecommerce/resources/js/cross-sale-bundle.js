/**
 * Cross-Sale Bundle JavaScript Module
 *
 * Generic cross-sale bundle functionality that can be used across themes.
 * Themes can override by setting window.EcommerceCrossSaleBundle before this loads.
 *
 * Required HTML structure:
 * - Container: [data-crosssale-bundle]
 * - Checkboxes: [data-crosssale-checkbox] with data-id, data-price, data-name
 * - Total price: [data-crosssale-total-price]
 * - Add all button: [data-crosssale-add-all] with data-url, data-parent-product
 * - Individual add buttons: [data-crosssale-add-btn] with data-url, data-id, data-parent-product
 * - Bundle items: [data-crosssale-bundle-item]
 */
;(function ($, window) {
    'use strict'

    // Allow themes to override config before initialization
    const defaultConfig = {
        // Selectors (can be overridden by themes)
        selectors: {
            container: '[data-crosssale-bundle]',
            checkbox: '[data-crosssale-checkbox]',
            totalPrice: '[data-crosssale-total-price]',
            addAllBtn: '[data-crosssale-add-all]',
            addBtn: '[data-crosssale-add-btn]',
            bundleItem: '[data-crosssale-bundle-item]',
        },
        // Currency formatting (can be set via window.siteConfig)
        currency: {
            symbol: window.siteConfig?.currencySymbol || '$',
            position: window.siteConfig?.currencyPosition || 'before',
            decimals: window.siteConfig?.currencyDecimals || 0,
        },
        // Event namespace
        namespace: 'crosssale',
    }

    // Merge with theme overrides
    const config = $.extend(true, {}, defaultConfig, window.EcommerceCrossSaleBundleConfig || {})

    // Store refresh URL globally
    window.crosssaleRefreshUrl = window.crosssaleRefreshUrl || null

    /**
     * Format price with currency symbol
     * Reads config from data-currency-config attribute on container, falls back to window.currencies
     */
    const formatPrice = (price, $container) => {
        // Try to get currency config from data attribute first (set by Blade)
        const dataConfig = $container ? $container.data('currency-config') : null
        const currencies = dataConfig || window.currencies || {}

        const decimals = currencies.decimals !== undefined
            ? currencies.decimals
            : (currencies.number_after_dot !== undefined ? currencies.number_after_dot : config.currency.decimals)
        const thousandsSep = currencies.thousands_separator || ','
        const decimalSep = currencies.decimal_separator || '.'
        const symbol = currencies.symbol || currencies.title || config.currency.symbol
        const isPrefix = currencies.is_prefix !== undefined
            ? currencies.is_prefix
            : (currencies.is_prefix_symbol !== undefined ? currencies.is_prefix_symbol : config.currency.position === 'before')

        const regex = '\\d(?=(\\d{3})+$)'
        let priceArr = price.toFixed(Math.max(0, ~~decimals)).toString().split('.')
        let formattedPrice = priceArr[0].replace(new RegExp(regex, 'g'), `$&${thousandsSep}`) +
            (priceArr[1] ? decimalSep + priceArr[1] : '')

        return isPrefix ? symbol + formattedPrice : formattedPrice + symbol
    }

    /**
     * Refresh cross-sale section when cart changes
     */
    const refreshCrosssaleSection = () => {
        if (!window.crosssaleRefreshUrl) {
            return
        }

        const $section = $(config.selectors.container)
        if ($section.length === 0) {
            return
        }

        // Add loading state
        $section.css('opacity', '0.5')

        $.ajax({
            url: window.crosssaleRefreshUrl,
            type: 'GET',
            success: ({ data }) => {
                $section.replaceWith(data)

                // Update lazy load if available
                if (typeof Theme !== 'undefined' && Theme.lazyLoadInstance) {
                    Theme.lazyLoadInstance.update()
                }

                // Re-initialize bundle
                $(document).trigger('crosssale-bundle-loaded')
            },
            error: (error) => {
                $section.css('opacity', '1')
                if (typeof Theme !== 'undefined' && Theme.handleError) {
                    Theme.handleError(error)
                }
            },
        })
    }

    /**
     * Initialize cross-sale bundle functionality
     */
    const initCrosssaleBundle = () => {
        const $section = $(config.selectors.container)
        if ($section.length === 0) return

        const $checkboxes = $section.find(config.selectors.checkbox)
        const $totalPrice = $section.find(config.selectors.totalPrice)
        const $addAllBtn = $section.find(config.selectors.addAllBtn)
        const ns = config.namespace

        /**
         * Update total price based on checked items
         */
        const updateTotal = () => {
            let total = 0
            let selectedCount = 0

            $checkboxes.filter(':checked').each(function () {
                const price = parseFloat($(this).data('price')) || 0
                total += price
                selectedCount++
            })

            $totalPrice.text(formatPrice(total, $section))
            $addAllBtn.prop('disabled', selectedCount === 0)
        }

        // Checkbox change handler
        $checkboxes.off(`change.${ns}`).on(`change.${ns}`, updateTotal)

        // Initialize total
        updateTotal()

        // Add all selected items to cart
        $addAllBtn.off(`click.${ns}`).on(`click.${ns}`, function () {
            const $btn = $(this)
            const selectedProducts = []
            const parentProduct = $btn.data('parent-product')

            $checkboxes.filter(':checked').each(function () {
                const productId = $(this).data('id')
                if (productId) {
                    selectedProducts.push(productId)
                }
            })

            if (selectedProducts.length === 0) return

            // Disable button while processing
            $btn.addClass('loading').prop('disabled', true)

            let index = 0
            let successCount = 0
            let lastResponseData = null

            const addNextProduct = () => {
                if (index >= selectedProducts.length) {
                    // All done - update UI
                    $btn.removeClass('loading')

                    if (lastResponseData) {
                        // Update cart count
                        if (lastResponseData.count !== undefined) {
                            $('.header__top-action-item .tp-header-action-cart span').text(lastResponseData.count)
                            $('.tp-cart-total-count').text(lastResponseData.count)
                        }
                        // Update mini cart
                        if (lastResponseData.cart_mini) {
                            $('.cartmini__area').html(lastResponseData.cart_mini)
                        }
                    }

                    // Uncheck all checkboxes
                    $checkboxes.prop('checked', false)
                    updateTotal()

                    // Refresh the section
                    refreshCrosssaleSection()

                    // Show success message
                    if (successCount > 0 && typeof Theme !== 'undefined' && Theme.showSuccess) {
                        Theme.showSuccess(`Added ${successCount} item(s) to cart`)
                    }
                    return
                }

                const productId = selectedProducts[index]
                $.ajax({
                    url: $btn.data('url'),
                    type: 'POST',
                    data: {
                        id: productId,
                        reference_product_for_crosssale: parentProduct,
                    },
                    success: (response) => {
                        successCount++
                        if (response.data) {
                            lastResponseData = response.data
                        }
                        index++
                        addNextProduct()
                    },
                    error: () => {
                        index++
                        addNextProduct()
                    },
                })
            }

            addNextProduct()
        })

        // Individual add buttons
        $section
            .find(config.selectors.addBtn)
            .off(`click.${ns}`)
            .on(`click.${ns}`, function (e) {
                e.preventDefault()
                e.stopPropagation()

                const $btn = $(this)
                const $item = $btn.closest(config.selectors.bundleItem)
                const $checkbox = $item.find(config.selectors.checkbox)
                const parentProduct = $addAllBtn.data('parent-product') || $btn.data('parent-product')
                const productId = $btn.data('id')
                const addUrl = $btn.data('url')

                // Add loading state
                $btn.addClass('loading').prop('disabled', true)

                $.ajax({
                    url: addUrl,
                    type: 'POST',
                    data: {
                        id: productId,
                        reference_product_for_crosssale: parentProduct,
                    },
                    success: (response) => {
                        // Check the checkbox
                        $checkbox.prop('checked', true)
                        updateTotal()

                        // Update cart UI
                        if (response.data) {
                            if (response.data.count !== undefined) {
                                $('.header__top-action-item .tp-header-action-cart span').text(response.data.count)
                                $('.tp-cart-total-count').text(response.data.count)
                            }
                            if (response.data.cart_mini) {
                                $('.cartmini__area').html(response.data.cart_mini)
                            }
                        }

                        // Dispatch event for other handlers
                        document.dispatchEvent(
                            new CustomEvent('ecommerce.cart.added', {
                                detail: { data: response.data, element: $btn[0] },
                            })
                        )

                        // Refresh section
                        refreshCrosssaleSection()
                    },
                    error: (error) => {
                        if (typeof Theme !== 'undefined' && Theme.handleError) {
                            Theme.handleError(error)
                        }
                    },
                    complete: () => {
                        $btn.removeClass('loading').prop('disabled', false)
                    },
                })
            })
    }

    /**
     * Initialize lazy loading for cross-sale sections
     */
    const initLazyLoading = () => {
        $('[data-bb-toggle="block-lazy-loading"]').each(function () {
            const $element = $(this)
            const url = $element.data('url')

            // Store cross-sale refresh URL
            if (url && url.includes('cross-sale-products')) {
                window.crosssaleRefreshUrl = url
            }
        })
    }

    // Public API
    window.EcommerceCrossSaleBundle = {
        config,
        formatPrice,
        refreshCrosssaleSection,
        initCrosssaleBundle,
        initLazyLoading,
    }

    // Auto-initialize on DOM ready
    $(function () {
        initLazyLoading()
        initCrosssaleBundle()

        // Listen for cart events
        document.addEventListener('ecommerce.cart.added', refreshCrosssaleSection)
        document.addEventListener('ecommerce.cart.removed', refreshCrosssaleSection)

        // Re-initialize after lazy loading
        $(document).on('crosssale-bundle-loaded', initCrosssaleBundle)
    })
})(jQuery, window)
