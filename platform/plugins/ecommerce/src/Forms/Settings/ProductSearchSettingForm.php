<?php

namespace Botble\Ecommerce\Forms\Settings;

use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Http\Requests\Settings\ProductSearchSettingRequest;
use Botble\Setting\Forms\SettingForm;

class ProductSearchSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/ecommerce::setting.product_search.product_search_settings'))
            ->setSectionDescription(
                trans('plugins/ecommerce::setting.product_search.product_search_settings_description')
            )
            ->setValidatorClass(ProductSearchSettingRequest::class)
            ->add('search_for_an_exact_phrase', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.product_search.form.search_for_an_exact_phrase'),
                'value' => get_ecommerce_setting('search_for_an_exact_phrase', false),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.product_search.form.search_for_an_exact_phrase_helper'),
                ],
                'attr' => [
                    'id' => 'search_for_an_exact_phrase',
                    'data-conflict-with' => 'enable_smart_search',
                ],
            ])
            ->add('enable_smart_search', 'onOffCheckbox', [
                'label' => trans('plugins/ecommerce::setting.product_search.form.enable_smart_search'),
                'value' => get_ecommerce_setting('enable_smart_search', true),
                'help_block' => [
                    'text' => trans('plugins/ecommerce::setting.product_search.form.enable_smart_search_helper'),
                ],
                'attr' => [
                    'id' => 'enable_smart_search',
                    'data-conflict-with' => 'search_for_an_exact_phrase',
                ],
            ])
            ->add(
                'search_minimum_word_length',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.minimum_word_length'))
                    ->helperText(trans('plugins/ecommerce::setting.product_search.form.minimum_word_length_helper'))
                    ->value(get_ecommerce_setting('search_minimum_word_length', 3))
                    ->defaultValue(3)
                    ->minNumber(1)
                    ->maxNumber(10)
                    ->toArray()
            )
            ->add(
                'search_products_by[]',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.search_products_by'))
                    ->choices([
                        'name' => trans('plugins/ecommerce::products.form.name'),
                        'sku' => trans('plugins/ecommerce::products.sku'),
                        'variation_sku' => trans('plugins/ecommerce::products.variation_sku'),
                        'barcode' => trans('plugins/ecommerce::products.form.barcode'),
                        'description' => trans('plugins/ecommerce::products.form.description'),
                        'brand' => trans('plugins/ecommerce::products.form.brand'),
                        'tag' => trans('plugins/ecommerce::products.form.tags'),
                        'category' => trans('plugins/ecommerce::products.form.categories'),
                    ])
                    ->selected(old('product_collections', EcommerceHelper::getProductsSearchBy()))
            )
            ->add(
                'enable_filter_products_by_categories',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_categories'))
                    ->value(EcommerceHelper::isEnabledFilterProductsByCategories())
                    ->defaultValue(true)
            )
            ->add(
                'enable_filter_products_by_brands',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_brands'))
                    ->value(EcommerceHelper::isEnabledFilterProductsByBrands())
                    ->defaultValue(true)
            )
            ->add(
                'enable_filter_products_by_rating',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_rating'))
                    ->value(EcommerceHelper::isEnabledFilterProductsByRating())
                    ->defaultValue(false)
            )
            ->add(
                'enable_filter_products_by_tags',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_tags'))
                    ->value($enableFilterByTags = EcommerceHelper::isEnabledFilterProductsByTags())
                    ->defaultValue(true)
            )
            ->addOpenCollapsible('enable_filter_products_by_tags', '1', $enableFilterByTags)
            ->add(
                'number_of_popular_tags_for_filter',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.number_of_popular_tags_for_filter'))
                    ->placeholder(trans('plugins/ecommerce::setting.product_search.form.number_of_popular_tags_for_filter_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.product_search.form.number_of_popular_tags_for_filter_helper'))
                    ->value(get_ecommerce_setting('number_of_popular_tags_for_filter', 10))
                    ->defaultValue(10)
            )
            ->addCloseCollapsible('enable_filter_products_by_tags', '1')
            ->add(
                'enable_filter_products_by_labels',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_labels'))
                    ->value(EcommerceHelper::isEnabledFilterProductsByLabels())
                    ->defaultValue(false)
            )
            ->add(
                'enable_filter_products_by_attributes',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(
                        trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_attributes')
                    )
                    ->value(EcommerceHelper::isEnabledFilterProductsByAttributes())
                    ->defaultValue(true)
            )
            ->add(
                'enable_filter_products_by_price',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(
                        trans('plugins/ecommerce::setting.product_search.form.enable_filter_products_by_price')
                    )
                    ->value($enableFilterByPrice = EcommerceHelper::isEnabledFilterProductsByPrice())
                    ->defaultValue(true)
            )
            ->addOpenCollapsible('enable_filter_products_by_price', '1', $enableFilterByPrice)
            ->add(
                'max_product_price_for_filter',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/ecommerce::setting.product_search.form.max_product_price_for_filter'))
                    ->placeholder(trans('plugins/ecommerce::setting.product_search.form.max_product_price_for_filter_placeholder'))
                    ->helperText(trans('plugins/ecommerce::setting.product_search.form.max_product_price_for_filter_helper', ['price' => format_price(EcommerceHelper::getProductMaxPrice())]))
                    ->value(get_ecommerce_setting('max_product_price_for_filter'))
            )
            ->addCloseCollapsible('enable_filter_products_by_price', '1');
    }
}
