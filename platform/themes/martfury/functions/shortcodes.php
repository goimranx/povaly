<?php

use Botble\Ads\Facades\AdsManager;
use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\CoreIconFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CoreIconField;
use Botble\Base\Forms\Fields\DatetimeField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Faq\Models\FaqCategory;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;

app()->booted(function (): void {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (method_exists(shortcode(), 'ignoreLazyLoading')) {
        shortcode()->ignoreLazyLoading(['site-features', 'flash-sale']);
    }

    if (is_plugin_active('ecommerce')) {
        add_shortcode(
            'featured-product-categories',
            __('Featured Product Categories'),
            __('Featured Product Categories'),
            function ($shortCode) {
                $categories = get_featured_product_categories(['take' => null]);

                return Theme::partial('short-codes.featured-product-categories', [
                    'title' => $shortCode->title,
                    'categories' => $categories,
                ]);
            }
        );

        shortcode()->setAdminConfig('featured-product-categories', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                );
        });

        shortcode()->registerLoadingState('featured-product-categories', Theme::getThemeNamespace('partials.short-codes.featured-product-categories-skeleton'));

        add_shortcode('featured-products', __('Featured products'), __('Featured products'), function ($shortCode) {
            $limit = (int) $shortCode->limit ?: 10;

            $products = get_featured_products(['take' => $limit]);

            $products = $limit > 1 ? $products : collect([$products]);

            return Theme::partial('short-codes.featured-products', [
                'title' => $shortCode->title,
                'limit' => $shortCode->limit,
                'products' => $products,
            ]);
        });

        shortcode()->setAdminConfig('featured-products', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->placeholder(__('Limit'))
                );
        });

        shortcode()->registerLoadingState('featured-products', Theme::getThemeNamespace('partials.short-codes.featured-products-skeleton'));

        add_shortcode('featured-brands', __('Featured Brands'), __('Featured Brands'), function ($shortCode) {
            $brands = get_featured_brands((int) $shortCode->limit ?: 8);

            return Theme::partial('short-codes.featured-brands', [
                'title' => $shortCode->title,
                'brands' => $brands,
            ]);
        });

        shortcode()->setAdminConfig('featured-brands', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Number of brands to display'))
                        ->placeholder(__('Number of brands to display'))
                        ->defaultValue(8)
                );
        });

        shortcode()->registerLoadingState('featured-brands', Theme::getThemeNamespace('partials.short-codes.featured-brands-skeleton'));

        add_shortcode(
            'product-collections',
            __('Product Collections'),
            __('Product Collections'),
            function (Shortcode $shortcode) {
                $productCollections = get_product_collections(
                    ['status' => BaseStatusEnum::PUBLISHED],
                    [],
                    ['id', 'name', 'slug']
                );

                if ($productCollections->isEmpty()) {
                    return null;
                }

                $limit = (int) $shortcode->limit ?: 8;

                $products = get_products_by_collections([
                    'collections' => [
                        'by' => 'id',
                        'value_in' => [$productCollections->first()->id],
                    ],
                    'take' => $limit,
                    'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                ]);

                return Theme::partial('short-codes.product-collections', [
                    'title' => $shortcode->title,
                    'productCollections' => $productCollections,
                    'limit' => $limit,
                    'products' => $products,
                ]);
            }
        );

        shortcode()->setAdminConfig('product-collections', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->placeholder(__('Limit'))
                        ->defaultValue(8)
                );
        });

        shortcode()->registerLoadingState('product-collections', Theme::getThemeNamespace('partials.short-codes.product-collections-skeleton'));

        add_shortcode(
            'product-category-products',
            __('Product category products'),
            __('Product category products'),
            function (Shortcode $shortcode) {
                $category = ProductCategory::query()
                    ->where([
                        'status' => BaseStatusEnum::PUBLISHED,
                        'id' => (int) $shortcode->category_id,
                    ])
                    ->with([
                        'children' => function ($query) {
                            return $query
                                ->wherePublished()
                                ->orderBy('order')
                                ->limit(3);
                        },
                    ])
                    ->first();

                if (! $category) {
                    return null;
                }

                $limit = (int) $shortcode->limit ?: 8;

                $products = app(ProductInterface::class)->getProductsByCategories([
                    'categories' => [
                        'by' => 'id',
                        'value_in' => array_merge([$category->id], $category->activeChildren->pluck('id')->all()),
                    ],
                    'take' => $limit,
                ]);

                return Theme::partial('short-codes.product-category-products', compact('category', 'products', 'limit'));
            }
        );

        shortcode()->setAdminConfig('product-category-products', function (array $attributes) {
            $categories = ProductCategory::query()
                ->wherePublished()
                ->orderBy('order')->oldest()
                ->get()
                ->pluck('name', 'id')
                ->all();

            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'category_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Select category'))
                        ->choices($categories)
                )
                ->add(
                    'number_of_categories',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit number of categories'))
                        ->placeholder(__('Default: 3'))
                        ->defaultValue(3)
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit number of products'))
                        ->placeholder(__('Unlimited by default'))
                );
        });

        shortcode()->registerLoadingState('product-category-products', Theme::getThemeNamespace('partials.short-codes.product-category-products-skeleton'));

        add_shortcode('trending-products', __('Trending Products'), __('Trending Products'), function ($shortCode) {
            $products = get_trending_products([
                    'take' => (int) $shortCode->limit ?: 10,
                ]);

            return Theme::partial('short-codes.trending-products', [
                'title' => $shortCode->title,
                'products' => $products,
            ]);
        });

        shortcode()->setAdminConfig('trending-products', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->placeholder(__('Limit'))
                        ->defaultValue(10)
                );
        });

        shortcode()->registerLoadingState('trending-products', Theme::getThemeNamespace('partials.short-codes.trending-products-skeleton'));

        add_shortcode('flash-sale', __('Flash sale'), __('Flash sale'), function ($shortCode) {
            if (! $shortCode->flash_sale_id) {
                return null;
            }

            $flashSale = FlashSale::query()
                ->notExpired()
                ->where('id', $shortCode->flash_sale_id)
                ->wherePublished()
                ->with([
                    'products' => function ($query) {
                        return $query
                            ->wherePublished()
                            ->with(EcommerceHelper::withProductEagerLoadingRelations());
                    },
                ])
                ->first();

            if (! $flashSale) {
                return null;
            }

            return Theme::partial('short-codes.flash-sale', [
                'title' => $shortCode->title,
                'flashSale' => $flashSale,
            ]);
        });

        shortcode()->setAdminConfig('flash-sale', function ($attributes) {
            $flashSales = FlashSale::query()
                ->wherePublished()
                ->notExpired()
                ->get()
                ->pluck('name', 'id')
                ->all();

            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                )
                ->add(
                    'flash_sale_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Select a flash sale'))
                        ->choices($flashSales)
                );
        });

        shortcode()->registerLoadingState('flash-sale', Theme::getThemeNamespace('partials.short-codes.flash-sale-skeleton'));
    }

    if (is_plugin_active('simple-slider')) {
        add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
            return Theme::getThemeNamespace() . '::partials.short-codes.sliders';
        }, 120);

        add_filter(SHORTCODE_REGISTER_CONTENT_IN_ADMIN, function ($data, $key, $attributes) {
            if ($key == 'simple-slider') {
                $ads = app(AdsInterface::class)->getModel()
                    ->where('status', BaseStatusEnum::PUBLISHED)
                    ->notExpired()
                    ->get()
                    ->pluck('name', 'key')
                    ->all();

                $maxAds = 2;

                $form = ShortcodeForm::createFromArray($attributes);

                for ($i = 1; $i <= $maxAds; $i++) {
                    $form->add(
                        'ads_' . $i,
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(__('Ad :number', ['number' => $i]))
                            ->choices(['' => __('-- select --')] + $ads)
                    );
                }

                return $data . $form->renderForm();
            }

            return $data;
        }, 50, 3);

        function get_ads_keys_from_shortcode(Shortcode $shortcode): array
        {
            $keys = collect($shortcode->toArray())
                ->sortKeys()
                ->filter(function ($value, $key) use ($shortcode) {
                    return Str::startsWith($key, 'ads_') ||
                        ($shortcode->name == 'theme-ads' && Str::startsWith($key, 'key_'));
                });

            return array_filter($keys->toArray() + [$shortcode->ads]);
        }
    }

    if (is_plugin_active('newsletter')) {
        add_shortcode('newsletter-form', __('Newsletter Form'), __('Newsletter Form'), function ($shortCode) {
            return Theme::partial('short-codes.newsletter-form', [
                'title' => $shortCode->title,
                'description' => $shortCode->description,
                'subtitle' => $shortCode->subtitle,
            ]);
        });

        shortcode()->setAdminConfig('newsletter-form', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                )
                ->add(
                    'subtitle',
                    TextareaField::class,
                    TextareaFieldOption::make()
                        ->label(__('Subtitle'))
                        ->placeholder(__('Subtitle'))
                        ->rows(3)
                )
                ->add(
                    'description',
                    TextareaField::class,
                    TextareaFieldOption::make()
                        ->label(__('Description'))
                        ->placeholder(__('Description'))
                        ->rows(3)
                );
        });
    }

    add_shortcode('download-app', __('Download Apps'), __('Download Apps'), function ($shortCode) {
        return Theme::partial('short-codes.download-app', [
            'title' => $shortCode->title,
            'description' => $shortCode->description,
            'subtitle' => $shortCode->subtitle,
            'screenshot' => $shortCode->screenshot,
            'androidAppUrl' => $shortCode->android_app_url,
            'iosAppUrl' => $shortCode->ios_app_url,
        ]);
    });

    shortcode()->setAdminConfig('download-app', function ($attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->placeholder(__('Title'))
            )
            ->add(
                'subtitle',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Subtitle'))
                    ->placeholder(__('Subtitle'))
                    ->rows(3)
            )
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
                    ->placeholder(__('Description'))
                    ->rows(3)
            )
            ->add(
                'screenshot',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Screenshot'))
            )
            ->add(
                'android_app_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Android app URL'))
                    ->placeholder(__('Android app URL'))
            )
            ->add(
                'ios_app_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('iOS app URL'))
                    ->placeholder(__('iOS app URL'))
            );
    });

    if (is_plugin_active('faq')) {
        add_shortcode('faq', __('FAQs'), __('FAQs'), function ($shortCode) {
            $categories = FaqCategory::query()
                ->wherePublished()
                ->with([
                    'faqs' => function ($query): void {
                        $query->wherePublished();
                    },
                ])
                ->orderBy('order', 'ASC')->latest()
                ->get();

            return Theme::partial('short-codes.faq', [
                'title' => $shortCode->title,
                'categories' => $categories,
            ]);
        });

        shortcode()->setAdminConfig('faq', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                        ->placeholder(__('Title'))
                );
        });
    }

    add_shortcode('site-features', __('Site features'), __('Site features'), function ($shortcode) {
        return Theme::partial('short-codes.site-features', compact('shortcode'));
    });

    shortcode()->setAdminConfig('site-features', function ($attributes) {
        $form = ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading();

        for ($i = 1; $i < 6; $i++) {
            $form->add(
                'icon' . $i,
                CoreIconField::class,
                CoreIconFieldOption::make()
                    ->label(__('Icon :number', ['number' => $i]))
            )
            ->add(
                'title' . $i,
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title :number', ['number' => $i]))
                    ->placeholder(__('Title :number', ['number' => $i]))
            )
            ->add(
                'subtitle' . $i,
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Subtitle :number', ['number' => $i]))
                    ->placeholder(__('Subtitle :number', ['number' => $i]))
            );
        }

        return $form;
    });

    if (is_plugin_active('contact') && defined('CONTACT_FORM_TEMPLATE_VIEW')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace() . '::partials.short-codes.contact-form';
        }, 120);
    }

    add_shortcode('contact-info-boxes', __('Contact info boxes'), __('Contact info boxes'), function ($shortCode) {
        return Theme::partial('short-codes.contact-info-boxes', ['title' => $shortCode->title]);
    });

    shortcode()->setAdminConfig('contact-info-boxes', function ($attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
                    ->placeholder(__('Title'))
            );
    });

    if (is_plugin_active('ads')) {
        add_shortcode('theme-ads', __('Theme ads'), __('Theme ads'), function ($shortCode) {
            $ads = [];
            $attributes = $shortCode->toArray();

            for ($i = 1; $i < 5; $i++) {
                if (isset($attributes['key_' . $i]) && ! empty($attributes['key_' . $i])) {
                    $ad = AdsManager::displayAds((string) $attributes['key_' . $i]);
                    if ($ad) {
                        $ads[] = $ad;
                    }
                }
            }

            $ads = array_filter($ads);

            return Theme::partial('short-codes.theme-ads', compact('ads'));
        });

        shortcode()->setAdminConfig('theme-ads', function ($attributes) {
            $ads = app(AdsInterface::class)->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->notExpired()
                ->get()
                ->pluck('name', 'key')
                ->all();

            $form = ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading();

            for ($i = 1; $i < 5; $i++) {
                $form->add(
                    'key_' . $i,
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ad :number', ['number' => $i]))
                        ->choices(['' => __('-- Select --')] + $ads)
                        ->searchable()
                );
            }

            return $form;
        });
    }

    add_shortcode('coming-soon', __('Coming soon'), __('Coming soon'), function ($shortCode) {
        return Theme::partial('short-codes.coming-soon', [
            'time' => $shortCode->time,
            'image' => $shortCode->image,
        ]);
    });

    shortcode()->setAdminConfig('coming-soon', function ($attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            ->add(
                'time',
                DatetimeField::class,
                DatePickerFieldOption::make()
                    ->label(__('Time'))
                    ->placeholder(__('Time'))
            )
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image'))
            );
    });

    if (is_plugin_active('language')) {
        add_shortcode(
            'locale-settings',
            __('Locale Settings'),
            __('Language and currency settings page'),
            function ($shortCode) {
                return Theme::partial('short-codes.locale-settings');
            }
        );

        shortcode()->setAdminConfig('locale-settings', function ($attributes) {
            return ShortcodeForm::createFromArray($attributes);
        });
    }

    // Deal and Offers Section Shortcode
    if (is_plugin_active('ecommerce')) {
        add_shortcode(
            'deal-and-offers',
            __('Deal and Offers Section'),
            __('Deal and Offers Section'),
            function (Shortcode $shortcode) {
                $dealProducts = collect();
                $offersProducts = collect();

                // Left side - Deal of the Day
                $dealType = $shortcode->deal_type ?: 'category';
                $dealLimit = (int) $shortcode->deal_limit ?: 1;

                if ($dealType === 'category' && $shortcode->deal_category_id) {
                    $dealProducts = app(ProductInterface::class)->getProductsByCategories([
                        'categories' => ['by' => 'id', 'value_in' => [(int) $shortcode->deal_category_id]],
                        'take' => $dealLimit,
                    ]);
                } elseif ($dealType === 'collection' && $shortcode->deal_collection_id) {
                    $dealProducts = get_products_by_collections([
                        'collections' => ['by' => 'id', 'value_in' => [(int) $shortcode->deal_collection_id]],
                        'take' => $dealLimit,
                        'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                    ]);
                } elseif ($dealType === 'product' && $shortcode->deal_product_ids) {
                    $productIds = explode(',', $shortcode->deal_product_ids);
                    $productIds = array_map('intval', array_filter($productIds));
                    $dealProducts = app(ProductInterface::class)->getProducts([
                        'condition' => [
                            ['ec_products.id', 'IN', $productIds],
                            ['ec_products.status', '=', BaseStatusEnum::PUBLISHED],
                        ],
                        'take' => $dealLimit,
                        'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                    ]);
                }

                // Right side - Offers
                $offersType = $shortcode->offers_type ?: 'category';
                $offersLimit = (int) $shortcode->offers_limit ?: 4;

                if ($offersType === 'category' && $shortcode->offers_category_id) {
                    $offersProducts = app(ProductInterface::class)->getProductsByCategories([
                        'categories' => ['by' => 'id', 'value_in' => [(int) $shortcode->offers_category_id]],
                        'take' => $offersLimit,
                    ]);
                } elseif ($offersType === 'collection' && $shortcode->offers_collection_id) {
                    $offersProducts = get_products_by_collections([
                        'collections' => ['by' => 'id', 'value_in' => [(int) $shortcode->offers_collection_id]],
                        'take' => $offersLimit,
                        'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                    ]);
                } elseif ($offersType === 'product' && $shortcode->offers_product_ids) {
                    $productIds = explode(',', $shortcode->offers_product_ids);
                    $productIds = array_map('intval', array_filter($productIds));
                    $offersProducts = app(ProductInterface::class)->getProducts([
                        'condition' => [
                            ['ec_products.id', 'IN', $productIds],
                            ['ec_products.status', '=', BaseStatusEnum::PUBLISHED],
                        ],
                        'take' => $offersLimit,
                        'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                    ]);
                }

                return Theme::partial('short-codes.deal-and-offers', [
                    'deal_title' => $shortcode->deal_title ?: 'Deal of the day',
                    'deal_products' => $dealProducts,
                    'deal_limit' => $dealLimit,
                    'offers_title' => $shortcode->offers_title ?: 'Offers',
                    'offers_link_text' => $shortcode->offers_link_text ?: 'Show all offers',
                    'offers_link_url' => $shortcode->offers_link_url ?: '#',
                    'offers_products' => $offersProducts,
                    'offers_limit' => $offersLimit,
                ]);
            }
        );

        shortcode()->setAdminConfig('deal-and-offers', function (array $attributes) {
            $categories = ProductCategory::query()
                ->wherePublished()
                ->orderBy('name')
                ->get()
                ->pluck('name', 'id')
                ->all();

            $collections = get_product_collections(
                ['status' => BaseStatusEnum::PUBLISHED],
                [],
                ['id', 'name']
            )->pluck('name', 'id')->all();

            $products = app(ProductInterface::class)->getProducts([
                'condition' => [['ec_products.status', '=', BaseStatusEnum::PUBLISHED]],
                'take' => null,
                'select' => ['ec_products.id', 'ec_products.name'],
                'order_by' => ['ec_products.name' => 'ASC'],
            ])->pluck('name', 'id')->all();

            $form = ShortcodeForm::createFromArray($attributes)
                ->withLazyLoading()
                ->add(
                    'deal_title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Deal Section Title'))
                        ->placeholder(__('Deal of the day'))
                        ->defaultValue('Deal of the day')
                )
                ->add(
                    'deal_type',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Deal Source Type'))
                        ->choices([
                            'category' => __('Category'),
                            'collection' => __('Collection'),
                            'product' => __('Specific Products'),
                        ])
                        ->defaultValue('category')
                        ->attributes([
                            'data-bb-toggle' => 'collapse',
                            'data-bb-target' => '.deal-source-wrapper',
                        ])
                );

            // Deal source fields with conditional display
            $dealType = $attributes['deal_type'] ?? 'category';
            
            $form->add('deal_source_category_open', 'html', [
                'html' => '<div class="deal-source-wrapper" data-bb-value="category"' . ($dealType !== 'category' ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'deal_category_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Deal Category'))
                    ->choices($categories)
                    ->searchable()
            )
            ->add('deal_source_category_close', 'html', ['html' => '</div>'])
            
            ->add('deal_source_collection_open', 'html', [
                'html' => '<div class="deal-source-wrapper" data-bb-value="collection"' . ($dealType !== 'collection' ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'deal_collection_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Deal Collection'))
                    ->choices($collections)
                    ->searchable()
            )
            ->add('deal_source_collection_close', 'html', ['html' => '</div>'])
            
            ->add('deal_source_product_open', 'html', [
                'html' => '<div class="deal-source-wrapper" data-bb-value="product"' . ($dealType !== 'product' ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'deal_product_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Deal Products'))
                    ->choices($products)
                    ->searchable()
                    ->multiple()
            )
            ->add('deal_source_product_close', 'html', ['html' => '</div>'])
            
            ->add(
                'deal_limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Deal Products Limit'))
                    ->placeholder(__('Number of products to show'))
                    ->defaultValue(1)
                    ->helperText(__('Show 1 for single product, or more for carousel'))
            )
            ->add(
                'offers_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Offers Section Title'))
                    ->placeholder(__('Offers'))
                    ->defaultValue('Offers')
            )
            ->add(
                'offers_link_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Offers Link Text'))
                    ->placeholder(__('Show all offers'))
                    ->defaultValue('Show all offers')
            )
            ->add(
                'offers_link_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Offers Link URL'))
                    ->placeholder(__('#'))
            )
            ->add(
                'offers_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Offers Source Type'))
                    ->choices([
                        'category' => __('Category'),
                        'collection' => __('Collection'),
                        'product' => __('Specific Products'),
                    ])
                    ->defaultValue('category')
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '.offers-source-wrapper',
                    ])
            );

            // Offers source fields with conditional display
            $offersType = $attributes['offers_type'] ?? 'category';
            
            $form->add('offers_source_category_open', 'html', [
                'html' => '<div class="offers-source-wrapper" data-bb-value="category"' . ($offersType !== 'category' ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'offers_category_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Offers Category'))
                    ->choices($categories)
                    ->searchable()
            )
            ->add('offers_source_category_close', 'html', ['html' => '</div>'])
            
            ->add('offers_source_collection_open', 'html', [
                'html' => '<div class="offers-source-wrapper" data-bb-value="collection"' . ($offersType !== 'collection' ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'offers_collection_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Offers Collection'))
                    ->choices($collections)
                    ->searchable()
            )
            ->add('offers_source_collection_close', 'html', ['html' => '</div>'])
            
            ->add('offers_source_product_open', 'html', [
                'html' => '<div class="offers-source-wrapper" data-bb-value="product"' . ($offersType !== 'product' ? ' style="display: none"' : '') . '>',
            ])
            ->add(
                'offers_product_ids',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Offers Products'))
                    ->choices($products)
                    ->searchable()
                    ->multiple()
            )
            ->add('offers_source_product_close', 'html', ['html' => '</div>'])
            
            ->add(
                'offers_limit',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Offers Products Limit'))
                    ->placeholder(__('Number of products to show'))
                    ->defaultValue(4)
                    ->helperText(__('Default: 4 products in carousel'))
            );

            return $form;
        });

        // Lazy loading disabled - renders directly for better performance
        // shortcode()->registerLoadingState('deal-and-offers', Theme::getThemeNamespace('partials.short-codes.deal-and-offers-skeleton'));
    }
});


    // Featured Banners Section
    add_shortcode(
        'featured-banners',
        __('Featured Banners'),
        __('Featured promotional banners with multiple layouts'),
        function ($shortCode) {
            return Theme::partial('short-codes.featured-banners', compact('shortCode'));
        }
    );

    shortcode()->setAdminConfig('featured-banners', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            // Top Row - Left and Right
            ->add(
                'left_top_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('1. Top Left - Image (Large)'))
                    ->helperText(__('Recommended Ratio: 21:9'))
            )
            ->add(
                'left_top_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Top Left - Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_top_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Top Left - Button Text'))
                    ->placeholder(__('Shop Now'))
            )
            ->add(
                'left_top_button_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Top Left - Button Link'))
                    ->placeholder('#')
            )
            
            // 2. Top Right
            ->add(
                'right_top_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('2. Top Right - Image'))
                    ->helperText(__('Recommended Ratio: 4:3.42'))
            )
            ->add(
                'right_top_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Top Right - Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_top_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Top Right - Button Text'))
                    ->placeholder(__('Shop Now'))
            )
            ->add(
                'right_top_button_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Top Right - Button Link'))
                    ->placeholder('#')
            )
            
            // 3. Bottom Left - Left Box (2 Side by Side Images)
            ->add(
                'left_bottom_left_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Title'))
                    ->placeholder(__('Title'))
            )
            ->add(
                'left_bottom_left_image1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Image 1'))
                    ->helperText(__('Recommended Ratio: 3:3.42 (or 9:16)'))
            )
            ->add(
                'left_bottom_left_image1_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Image 1 Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_bottom_left_image1_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Image 1 Text'))
                    ->placeholder(__('Text'))
            )
            ->add(
                'left_bottom_left_image2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Image 2'))
                    ->helperText(__('Recommended Ratio: 3:3.42 (or 9:16)'))
            )
            ->add(
                'left_bottom_left_image2_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Image 2 Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_bottom_left_image2_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Bottom Left - Left Box Image 2 Text'))
                    ->placeholder(__('Text'))
            )
            
            // Bottom Left - Right Box (Header + 2x2 Grid)
            ->add(
                'left_bottom_right_header_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Header'))
                    ->placeholder(__('Header'))
            )
            ->add(
                'left_bottom_right_header_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Header Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_bottom_right_image1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 1'))
                    ->helperText(__('Recommended Ratio: 1:1 (square)'))
            )
            ->add(
                'left_bottom_right_image1_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 1 Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_bottom_right_image2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 2'))
                    ->helperText(__('Recommended Ratio: 1:1 (square)'))
            )
            ->add(
                'left_bottom_right_image2_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 2 Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_bottom_right_image3',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 3'))
                    ->helperText(__('Recommended Ratio: 1:1 (square)'))
            )
            ->add(
                'left_bottom_right_image3_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 3 Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_bottom_right_image4',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 4'))
                    ->helperText(__('Recommended Ratio: 1:1 (square)'))
            )
            ->add(
                'left_bottom_right_image4_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Bottom Left - Right Box Grid Image 4 Link'))
                    ->placeholder('#')
            )
            
            // 5. Bottom Right (Header + 2 Stacked Images)
            ->add(
                'right_bottom_header_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Bottom Right - Header'))
                    ->placeholder(__('View More'))
            )
            ->add(
                'right_bottom_header_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Bottom Right - Header Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_bottom_image1',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('5. Bottom Right - Image 1'))
                    ->helperText(__('Recommended Ratio: 3:3.9 (or 9:16)'))
            )
            ->add(
                'right_bottom_image1_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Bottom Right - Image 1 Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_bottom_image2',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('5. Bottom Right - Image 2'))
                    ->helperText(__('Recommended Ratio: 3:3.9 (or 9:16)'))
            )
            ->add(
                'right_bottom_image2_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Bottom Right - Image 2 Link'))
                    ->placeholder('#')
            );
    });

    shortcode()->registerLoadingState('featured-banners', Theme::getThemeNamespace('partials.short-codes.featured-banners-skeleton'));

    // Promotional Hero Grid Section
    add_shortcode(
        'promotional-hero-grid',
        __('Promotional Hero Grid'),
        __('Hero banner with promotional grid layout'),
        function ($shortCode) {
            return Theme::partial('short-codes.promotional-hero-grid', compact('shortCode'));
        }
    );

    shortcode()->setAdminConfig('promotional-hero-grid', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->withLazyLoading()
            // 1. Left - Large Banner
            ->add(
                'left_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('1. Left - Large Image'))
                    ->helperText(__('Recommended Ratio: 2.2:1.8 (or 11:9)'))
            )
            ->add(
                'left_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Left - Link'))
                    ->placeholder('#')
            )
            ->add(
                'left_subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Left - Subtitle'))
                    ->placeholder(__('New Arrival'))
            )
            ->add(
                'left_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Left - Title'))
                    ->placeholder(__('Amazing Products'))
            )
            ->add(
                'left_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('1. Left - Button Text'))
                    ->placeholder(__('Shop Now'))
            )
            // 2. Middle - Top Vertical Banner
            ->add(
                'right_top_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('2. Middle - Top Vertical Image'))
                    ->helperText(__('Recommended Ratio: 1.8:1.08 (or 5:3)'))
            )
            ->add(
                'right_top_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Middle - Top Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_top_subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Middle - Top Subtitle'))
                    ->placeholder(__('Hot Deal'))
            )
            ->add(
                'right_top_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Middle - Top Title'))
                    ->placeholder(__('Special Offer'))
            )
            ->add(
                'right_top_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('2. Middle - Top Button Text'))
                    ->placeholder(__('View More'))
            )
            // 3. Middle - Bottom Left Banner
            ->add(
                'right_middle_left_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('3. Middle - Bottom Left Image'))
                    ->helperText(__('Recommended Ratio: 0.9:0.72 (or 5:4)'))
            )
            ->add(
                'right_middle_left_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Middle - Bottom Left Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_middle_left_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Middle - Bottom Left Title'))
                    ->placeholder(__('Category Name'))
            )
            ->add(
                'right_middle_left_subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Middle - Bottom Left Subtitle'))
                    ->placeholder(__('Description'))
            )
            ->add(
                'right_middle_left_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('3. Middle - Bottom Left Button Text'))
                    ->placeholder(__('Explore'))
            )
            // 4. Middle - Bottom Right Banner
            ->add(
                'right_middle_right_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('4. Middle - Bottom Right Image'))
                    ->helperText(__('Recommended Ratio: 0.9:0.72 (or 5:4)'))
            )
            ->add(
                'right_middle_right_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Middle - Bottom Right Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_middle_right_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Middle - Bottom Right Title'))
                    ->placeholder(__('Category Name'))
            )
            ->add(
                'right_middle_right_subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Middle - Bottom Right Subtitle'))
                    ->placeholder(__('Description'))
            )
            ->add(
                'right_middle_right_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('4. Middle - Bottom Right Button Text'))
                    ->placeholder(__('Explore'))
            )
            // 5. Right - Tall Banner
            ->add(
                'right_bottom_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('5. Right - Tall Image'))
                    ->helperText(__('Recommended Ratio: 1.2:1.8 (or 2:3)'))
            )
            ->add(
                'right_bottom_link',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Right - Link'))
                    ->placeholder('#')
            )
            ->add(
                'right_bottom_subtitle',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Right - Subtitle'))
                    ->placeholder(__('Limited Time'))
            )
            ->add(
                'right_bottom_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Right - Title'))
                    ->placeholder(__('Flash Sale'))
            )
            ->add(
                'right_bottom_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('5. Right - Button Text'))
                    ->placeholder(__('Shop Now'))
            );
    });

    shortcode()->registerLoadingState('promotional-hero-grid', Theme::getThemeNamespace('partials.short-codes.promotional-hero-grid-skeleton'));
