# Implementation Plan: Smart Products Shortcode System

This plan implements a sophisticated product recommendation system for the Laravel Botble multivendor marketplace with 7 algorithmic criteria.

## Feature 1: SmartProductService - Core Algorithm Engine

- [ ] 1. Create SmartProductService class at `platform/plugins/ecommerce/src/Services/Products/SmartProductService.php`
      
      Namespace: `Botble\Ecommerce\Services\Products\SmartProductService`
      Constructor: `__construct(protected ProductInterface $productRepository) {}`
      
      Implement 7 public methods, each returning `Collection` of Product models:
      - `getMostViewed(int $limit, ?int $categoryId = null)` - ORDER BY ec_products.views DESC
      - `getMostSold(int $limit, ?int $categoryId = null)` - JOIN ec_order_product, SUM qty WHERE ec_orders.status = COMPLETED
      - `getTrending(int $limit, ?int $categoryId = null, int $days = 7)` - Weighted score: (views_last_N_days * 2) + (orders_last_N_days * 10)
      - `getMostWishlisted(int $limit, ?int $categoryId = null)` - JOIN ec_wish_lists, COUNT per product_id
      - `getBestRated(int $limit, ?int $categoryId = null)` - ORDER BY (reviews_avg * LOG(reviews_count + 1)) DESC
      - `getMostAddedToCart(int $limit, ?int $categoryId = null)` - Uses views as proxy (no persistent cart table)
      - `getSimilarProducts(int $productId, int $limit)` - Same category, price ±20% of reference product
      
      All methods:
      - Filter: status = PUBLISHED, is_variation = 0
      - If marketplace plugin active: JOIN ec_stores WHERE store.status = published
      - Optional categoryId: JOIN ec_product_categories WHERE category_id = ?
      - Use `EcommerceHelper::withProductEagerLoadingRelations()` + ['reviews', 'store']
      
      Files: `platform/plugins/ecommerce/src/Services/Products/SmartProductService.php`
      
      Verify: 
      - `php artisan tinker` then `$service = app('Botble\Ecommerce\Services\Products\SmartProductService'); $service->getMostViewed(5);` returns Collection
      - `$service->getMostSold(5);` returns products with actual completed orders
      - `$service->getTrending(5, null, 7);` returns Collection ordered by trending score
      - Verify variations excluded: `$service->getMostViewed(100)->filter(fn($p) => $p->is_variation == 1)->isEmpty()` should be true

## Feature 2: Shortcode Registration & Blade Template

- [ ] 2. Register 'smart-products' shortcode in `platform/themes/martfury/functions/shortcodes.php`
      
      Add after 'trending-products' block, before 'flash-sale':
      
      ```php
      add_shortcode('smart-products', __('Smart Products'), __('Smart Products'), function ($shortCode) {
          $service = app('Botble\Ecommerce\Services\Products\SmartProductService');
          $criteria = $shortCode->criteria ?: 'most_viewed';
          $itemsToShow = (int)$shortCode->items_to_show ?: 12;
          $itemsPerPage = (int)$shortCode->items_per_page ?: 6;
          $loadMoreType = $shortCode->load_more_type ?: 'button';
          $categoryId = $shortCode->category_id ? (int)$shortCode->category_id : null;
          
          $products = match($criteria) {
              'most_sold' => $service->getMostSold($itemsToShow, $categoryId),
              'trending' => $service->getTrending($itemsToShow, $categoryId, 7),
              'most_wishlisted' => $service->getMostWishlisted($itemsToShow, $categoryId),
              'best_rated' => $service->getBestRated($itemsToShow, $categoryId),
              'most_added_to_cart' => $service->getMostAddedToCart($itemsToShow, $categoryId),
              default => $service->getMostViewed($itemsToShow, $categoryId),
          };
          
          return Theme::partial('short-codes.smart-products', compact('title', 'criteria', 'products', 'itemsPerPage', 'loadMoreType', 'categoryId'));
      });
      
      shortcode()->setAdminConfig('smart-products', function($attributes) {
          $categories = ProductCategory::wherePublished()->pluck('name', 'id')->toArray();
          return ShortcodeForm::createFromArray($attributes)
              ->withLazyLoading()
              ->add('title', TextField::class, TextFieldOption::make()->label(__('Title')))
              ->add('criteria', SelectField::class, SelectFieldOption::make()
                  ->label(__('Criteria'))
                  ->choices([
                      'most_viewed' => 'Most Viewed',
                      'most_sold' => 'Best Sellers',
                      'trending' => 'Trending',
                      'most_wishlisted' => 'Most Wishlisted',
                      'best_rated' => 'Best Rated',
                      'most_added_to_cart' => 'Most Added to Cart'
                  ])
                  ->defaultValue('most_viewed'))
              ->add('items_to_show', NumberField::class, NumberFieldOption::make()->label(__('Total items'))->defaultValue(12))
              ->add('items_per_page', NumberField::class, NumberFieldOption::make()->label(__('Items per page'))->defaultValue(6))
              ->add('load_more_type', SelectField::class, SelectFieldOption::make()
                  ->label(__('Load More Type'))
                  ->choices(['none' => 'None', 'button' => 'Load More Button', 'auto' => 'Auto Load'])
                  ->defaultValue('button'))
              ->add('category_id', SelectField::class, SelectFieldOption::make()
                  ->label(__('Category (optional)'))
                  ->choices(['' => __('All')] + $categories));
      });
      ```
      
      Files: `platform/themes/martfury/functions/shortcodes.php`
      
      Verify: Admin panel shows 'Smart Products' in shortcode picker with all config fields

- [ ] 3. Create Blade template `platform/themes/martfury/partials/short-codes/smart-products.blade.php`
      
      Copy structure from `featured-products.blade.php`:
      - Container: `.ps-featured-products > .ps-container > .ps-block--featured`
      - Header: `.ps-block__header` with `<h3>{{ $title }}</h3>`
      - Grid: `.ps-grid--featured.js-smart-products-grid` with data attributes
      - Loop products: `@include('theme.martfury::partials.product-card-enhanced', ['product' => $product, 'layout' => 'flyout'])`
      - Footer: Load More button `.js-load-more-smart-products` (conditionally shown)
      
      Data attributes on grid:
      - `data-criteria="{{ $criteria }}"`
      - `data-category-id="{{ $categoryId }}"`
      - `data-load-more-type="{{ $loadMoreType }}"`
      - `data-items-per-page="{{ $itemsPerPage }}"`
      - `data-current-page="1"`
      - `data-total-shown="{{ $products->count() }}"`
      
      Files: `platform/themes/martfury/partials/short-codes/smart-products.blade.php`
      
      Verify: 
      - Insert `[smart-products title="Trending" criteria="trending"]` on homepage
      - Products display in 6-column grid matching featured-products layout
      - Load More button appears at bottom
      - Inspect grid element - verify data attributes present

## Feature 3: AJAX Endpoint & Frontend JavaScript

- [ ] 4. Add AJAX route in `platform/plugins/ecommerce/routes/base.php`
      
      After `ajax/recently-viewed-products` route (around line 340):
      
      ```php
      Route::get('ajax/smart-products', [
          'uses' => 'PublicProductController@ajaxGetSmartProducts',
          'as' => 'public.ajax.smart-products',
      ]);
      ```
      
      Files: `platform/plugins/ecommerce/routes/base.php`
      
      Verify: `php artisan route:list | grep smart-products` shows the route

- [ ] 5. Implement AJAX controller method in `platform/plugins/ecommerce/src/Http/Controllers/Fronts/PublicProductController.php`
      
      Add method at end of class:
      
      ```php
      public function ajaxGetSmartProducts(Request $request, SmartProductService $smartProductService)
      {
          $request->validate([
              'criteria' => 'required|string|in:most_viewed,most_sold,trending,most_wishlisted,best_rated,most_added_to_cart',
              'category_id' => 'nullable|integer',
              'page' => 'required|integer|min:1',
              'per_page' => 'required|integer|min:1|max:50',
          ]);
          
          $criteria = $request->input('criteria');
          $categoryId = $request->input('category_id') ? (int)$request->input('category_id') : null;
          $page = (int)$request->input('page');
          $perPage = (int)$request->input('per_page');
          $offset = ($page - 1) * $perPage;
          $limit = $perPage + 1;
          
          $products = match($criteria) {
              'most_sold' => $smartProductService->getMostSold($offset + $limit, $categoryId),
              'trending' => $smartProductService->getTrending($offset + $limit, $categoryId, 7),
              'most_wishlisted' => $smartProductService->getMostWishlisted($offset + $limit, $categoryId),
              'best_rated' => $smartProductService->getBestRated($offset + $limit, $categoryId),
              'most_added_to_cart' => $smartProductService->getMostAddedToCart($offset + $limit, $categoryId),
              default => $smartProductService->getMostViewed($offset + $limit, $categoryId),
          };
          
          $products = $products->slice($offset, $limit);
          $hasMore = $products->count() > $perPage;
          if ($hasMore) {
              $products = $products->take($perPage);
          }
          
          $html = '';
          foreach ($products as $product) {
              $html .= Theme::scope('partials.product-card-enhanced', ['product' => $product, 'layout' => 'flyout'], 'theme.martfury::partials.product-card-enhanced')->render();
          }
          
          return $this->httpResponse()->setData([
              'success' => true,
              'data' => [
                  'html' => $html,
                  'hasMore' => $hasMore,
                  'currentPage' => $page,
                  'totalShown' => $offset + $products->count(),
              ],
          ]);
      }
      ```
      
      Files: `platform/plugins/ecommerce/src/Http/Controllers/Fronts/PublicProductController.php`
      
      Verify: Visit `/ajax/smart-products?criteria=most_viewed&page=1&per_page=6` - returns JSON with success:true

- [ ] 6. Create frontend JavaScript `platform/themes/martfury/assets/js/smart-products.js`
      
      Implement:
      - `initSmartProductsGrid(grid)` - sets up Load More button or IntersectionObserver
      - `loadMoreProducts(grid, btn, loadingEl)` - fetches via AJAX, appends HTML, updates data attributes
      - `setupInfiniteScroll(grid, loadingEl)` - creates sentinel div, observes with IntersectionObserver
      
      Error handling: console.error + user-friendly message in loadingEl
      
      Register in `platform/themes/martfury/config.php` under 'javascript' array, or include via `<script>` tag in template
      
      Files: `platform/themes/martfury/assets/js/smart-products.js`, `platform/themes/martfury/config.php`
      
      Verify:
      - Click Load More button - DevTools Network shows /ajax/smart-products request
      - Products append to grid (6 → 12 → 18...)
      - Button disappears when no more products
      - Test auto-load: shortcode with `load_more_type="auto"`, scroll down - products load automatically
      - Test error: block AJAX in DevTools - error message appears

## Cross-Feature Integration Test

- [ ] 7. End-to-end verification
      
      1. Insert shortcode: `[smart-products title="Best Sellers" criteria="most_sold" items_to_show="18" items_per_page="6" load_more_type="button" category_id="5"]`
      2. Visit page - verify 6 products from category 5 display
      3. Click Load More 2 times - verify 18 products total
      4. Change to `criteria="trending"` - verify different products appear
      5. Change to `load_more_type="auto"` - verify infinite scroll works
      6. Mobile test: view on 480px width - grid collapses to 1 column
      7. Console check: no JavaScript errors
      
      Files: All files from steps 1-6
      
      Verify: Complete user flow works without errors, products display correctly, AJAX pagination functions

---

## Gotchas & Notes

1. **Cart Facade unavailable on frontend**: No persistent cart table exists. `most_added_to_cart` uses views as proxy.
2. **Multivendor safety**: All queries must filter by store approval status if marketplace plugin active.
3. **Reviews**: Use `reviews_avg` and `reviews_count` columns from `ec_products` table (aggregate fields).
4. **Eager loading**: Always use `EcommerceHelper::withProductEagerLoadingRelations()` to avoid N+1 queries.
5. **Category filtering**: When categoryId provided, join `ec_product_categories` on `product_id`.
6. **Trending calculation**: Configurable days parameter (default 7) for time-based trending score.
7. **Similar products**: Calculate price range ±20% based on `front_sale_price` (not `price`).
8. **AJAX pagination**: Fetch limit+1 to detect `hasMore` without separate count query.
9. **JavaScript loading**: Ensure smart-products.js loads globally or per-shortcode to avoid script missing errors.
10. **Blade rendering in AJAX**: Use `Theme::scope()->render()` not just `view()` to maintain theme context.

