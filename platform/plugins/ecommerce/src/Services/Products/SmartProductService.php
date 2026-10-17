<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SmartProductService
{
    public function __construct(protected ProductInterface $productRepository)
    {
    }

    /**
     * Get most viewed products
     */
    public function getMostViewed(int $limit, ?int $categoryId = null): Collection
    {
        $query = $this->baseQuery($categoryId);
        
        return $query->orderBy('ec_products.views', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get best selling products (most sold)
     */
    public function getMostSold(int $limit, ?int $categoryId = null): Collection
    {
        $query = $this->baseQuery($categoryId);
        
        $query->leftJoin('ec_order_product', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->leftJoin('ec_orders', 'ec_order_product.order_id', '=', 'ec_orders.id')
            ->where(function($q) {
                $q->where('ec_orders.status', 'completed')
                  ->orWhereNull('ec_orders.status');
            })
            ->select('ec_products.*', DB::raw('COALESCE(SUM(ec_order_product.qty), 0) as total_sold'))
            ->groupBy('ec_products.id')
            ->orderBy('total_sold', 'DESC')
            ->limit($limit);
        
        return $query->get();
    }

    /**
     * Get trending products (recent views + recent sales weighted)
     */
    public function getTrending(int $limit, ?int $categoryId = null, int $days = 7): Collection
    {
        $query = $this->baseQuery($categoryId);
        $daysAgo = now()->subDays($days);
        
        $query->leftJoin('ec_product_views', function($join) use ($daysAgo) {
                $join->on('ec_products.id', '=', 'ec_product_views.product_id')
                     ->where('ec_product_views.date', '>=', $daysAgo);
            })
            ->leftJoin('ec_order_product', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->leftJoin('ec_orders', function($join) use ($daysAgo) {
                $join->on('ec_order_product.order_id', '=', 'ec_orders.id')
                     ->where('ec_orders.status', 'completed')
                     ->where('ec_orders.created_at', '>=', $daysAgo);
            })
            ->select('ec_products.*', 
                DB::raw('(COALESCE(COUNT(DISTINCT ec_product_views.id), 0) * 2 + COALESCE(SUM(ec_order_product.qty), 0) * 10) as trending_score'))
            ->groupBy('ec_products.id')
            ->orderBy('trending_score', 'DESC')
            ->limit($limit);
        
        return $query->get();
    }

    /**
     * Get most wishlisted products
     */
    public function getMostWishlisted(int $limit, ?int $categoryId = null): Collection
    {
        $query = $this->baseQuery($categoryId);
        
        $query->leftJoin('ec_wish_lists', 'ec_products.id', '=', 'ec_wish_lists.product_id')
            ->select('ec_products.*', DB::raw('COALESCE(COUNT(ec_wish_lists.id), 0) as wishlist_count'))
            ->groupBy('ec_products.id')
            ->orderBy('wishlist_count', 'DESC')
            ->limit($limit);
        
        return $query->get();
    }

    /**
     * Get best rated products
     */
    public function getBestRated(int $limit, ?int $categoryId = null): Collection
    {
        $query = $this->baseQuery($categoryId);
        
        $query->where('ec_products.reviews_count', '>', 0)
            ->select('ec_products.*', 
                DB::raw('(ec_products.reviews_avg * LOG(ec_products.reviews_count + 1)) as rating_score'))
            ->orderBy('rating_score', 'DESC')
            ->limit($limit);
        
        return $query->get();
    }

    /**
     * Get most added to cart (using views as proxy)
     */
    public function getMostAddedToCart(int $limit, ?int $categoryId = null): Collection
    {
        // Using views as proxy since there's no persistent cart tracking
        return $this->getMostViewed($limit, $categoryId);
    }

    /**
     * Get similar products based on category and price range
     */
    public function getSimilarProducts(int $productId, int $limit): Collection
    {
        $product = $this->productRepository->findById($productId);
        
        if (!$product) {
            return collect();
        }
        
        $priceMin = $product->front_sale_price * 0.8;
        $priceMax = $product->front_sale_price * 1.2;
        
        $query = $this->baseQuery();
        
        // Same categories
        $categoryIds = $product->categories()->pluck('ec_product_categories.id')->toArray();
        
        if (!empty($categoryIds)) {
            $query->join('ec_product_categories', 'ec_products.id', '=', 'ec_product_categories.product_id')
                ->whereIn('ec_product_categories.category_id', $categoryIds);
        }
        
        $query->where('ec_products.id', '!=', $productId)
            ->whereBetween('ec_products.front_sale_price', [$priceMin, $priceMax])
            ->orderBy('ec_products.views', 'DESC')
            ->limit($limit);
        
        return $query->get();
    }

    /**
     * Base query with common filters
     */
    protected function baseQuery(?int $categoryId = null)
    {
        $query = DB::table('ec_products')
            ->where('ec_products.status', 'published')
            ->where('ec_products.is_variation', 0);
        
        // Multivendor filtering
        if (is_plugin_active('marketplace')) {
            $query->leftJoin('mp_stores', 'ec_products.store_id', '=', 'mp_stores.id')
                ->where(function($q) {
                    $q->where('mp_stores.status', 'published')
                      ->orWhereNull('mp_stores.id');
                });
        }
        
        // Category filtering
        if ($categoryId) {
            $query->join('ec_product_categories', 'ec_products.id', '=', 'ec_product_categories.product_id')
                ->where('ec_product_categories.category_id', $categoryId);
        }
        
        // Transform to Product models with eager loading
        $productIds = $query->pluck('ec_products.id')->toArray();
        
        if (empty($productIds)) {
            return collect();
        }
        
        return $this->productRepository
            ->getModel()
            ->whereIn('id', $productIds)
            ->with(array_merge(
                EcommerceHelper::withProductEagerLoadingRelations(),
                ['reviews']
            ));
    }
}
