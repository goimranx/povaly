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
     * Get smart products with combined scoring from multiple criteria
     * 
     * @param int $limit Number of products to return
     * @param int|null $categoryId Optional category filter
     * @param array $weights Scoring weights for each criteria
     * @return Collection
     */
    public function getSmartProducts(int $limit, ?int $categoryId = null, array $weights = []): Collection
    {
        // Default weights for each criteria (adjustable)
        $defaultWeights = [
            'views' => 1.0,           // Current views weight
            'sales' => 10.0,          // Sales weight (higher because fewer sales)
            'recent_views' => 2.0,    // Recent views (trending)
            'recent_sales' => 15.0,   // Recent sales (trending)
            'wishlists' => 5.0,       // Wishlist weight
            'rating' => 3.0,          // Rating weight
        ];
        
        $weights = array_merge($defaultWeights, $weights);
        $daysAgo = now()->subDays(7)->format('Y-m-d H:i:s');
        
        // Build base query - use DB::raw for string comparisons to avoid strict mode issues
        $query = DB::table('ec_products')
            ->whereRaw("ec_products.status = 'published'")
            ->whereRaw("ec_products.is_variation = 0");
        
        // Multivendor filtering
        if (is_plugin_active('marketplace')) {
            $query->leftJoin('mp_stores', 'ec_products.store_id', '=', 'mp_stores.id')
                ->whereRaw("(`mp_stores`.`status` = 'published' OR `mp_stores`.`id` IS NULL)");
        }
        
        // Category filtering
        if ($categoryId) {
            $query->join('ec_product_categories', 'ec_products.id', '=', 'ec_product_categories.product_id')
                ->where('ec_product_categories.category_id', $categoryId);
        }
        
        // Join all data sources for scoring using DB::raw with properly bound values
        $query
            // All time sales
            ->leftJoin(DB::raw('(
                SELECT op.product_id, SUM(op.qty) as total_sold
                FROM ec_order_product op
                INNER JOIN ec_orders o ON op.order_id = o.id
                WHERE o.status = \'completed\'
                GROUP BY op.product_id
            ) as sales'), 'ec_products.id', '=', 'sales.product_id')
            // Recent sales (trending)
            ->leftJoin(DB::raw('(
                SELECT op.product_id, SUM(op.qty) as recent_sold
                FROM ec_order_product op
                INNER JOIN ec_orders o ON op.order_id = o.id
                WHERE o.status = \'completed\' AND o.created_at >= \'' . $daysAgo . '\'
                GROUP BY op.product_id
            ) as recent_sales'), 'ec_products.id', '=', 'recent_sales.product_id')
            // Wishlists count
            ->leftJoin(DB::raw('(
                SELECT 
                    product_id,
                    COUNT(*) as wishlist_count
                FROM ec_wish_lists
                GROUP BY product_id
            ) as wishlists'), 'ec_products.id', '=', 'wishlists.product_id');
        
        // First, get recent views count per product (using raw to avoid binding issues)
        $query->leftJoin(DB::raw('(
            SELECT product_id, COUNT(*) as recent_view_count
            FROM ec_product_views
            WHERE date >= \'' . $daysAgo . '\'
            GROUP BY product_id
        ) as recent_view_counts'), 'ec_products.id', '=', 'recent_view_counts.product_id');
        
        // Calculate combined score using ANY_VALUE() for non-aggregated columns
        // to satisfy MySQL ONLY_FULL_GROUP_BY mode
        $query->select(
            'ec_products.id',
            DB::raw(sprintf('(
                (COALESCE(ANY_VALUE(ec_products.views), 0) * %f) +
                (COALESCE(sales.total_sold, 0) * %f) +
                (COALESCE(recent_view_counts.recent_view_count, 0) * %f) +
                (COALESCE(recent_sales.recent_sold, 0) * %f) +
                (COALESCE(wishlists.wishlist_count, 0) * %f) +
                (COALESCE(ANY_VALUE(ec_products.reviews_avg) * LOG(COALESCE(ANY_VALUE(ec_products.reviews_count), 0) + 1), 0) * %f)
            ) as smart_score',
                $weights['views'],
                $weights['sales'],
                $weights['recent_views'],
                $weights['recent_sales'],
                $weights['wishlists'],
                $weights['rating']
            ))
        );
        
        $query->groupBy('ec_products.id')
            ->orderBy('smart_score', 'DESC')
            ->limit($limit * 2); // Get more for diversity
        
        $productIds = $query->pluck('ec_products.id')->toArray();
        
        if (empty($productIds)) {
            return collect();
        }
        
        // Get products with eager loading, maintaining order
        $products = $this->productRepository
            ->getModel()
            ->whereIn('id', $productIds)
            ->with(array_merge(
                EcommerceHelper::withProductEagerLoadingRelations(),
                ['reviews']
            ))
            ->get();
        
        // Sort by original order and apply vendor diversity
        $orderedProducts = collect($productIds)
            ->map(fn($id) => $products->firstWhere('id', $id))
            ->filter();
        
        // Apply vendor diversity (don't show too many from same vendor)
        return $this->applyVendorDiversity($orderedProducts, $limit);
    }

    /**
     * Get most viewed products (fallback/simple mode)
     */
    public function getMostViewed(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 10.0,
            'sales' => 0,
            'recent_views' => 0,
            'recent_sales' => 0,
            'wishlists' => 0,
            'rating' => 0,
        ]);
    }

    /**
     * Get best selling products
     */
    public function getMostSold(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0,
            'sales' => 100.0,
            'recent_views' => 0,
            'recent_sales' => 0,
            'wishlists' => 0,
            'rating' => 0,
        ]);
    }

    /**
     * Get trending products (emphasize recent activity)
     */
    public function getTrending(int $limit, ?int $categoryId = null, int $days = 7): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0.5,
            'sales' => 2.0,
            'recent_views' => 10.0,
            'recent_sales' => 50.0,
            'wishlists' => 1.0,
            'rating' => 1.0,
        ]);
    }

    /**
     * Get most wishlisted products
     */
    public function getMostWishlisted(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0,
            'sales' => 0,
            'recent_views' => 0,
            'recent_sales' => 0,
            'wishlists' => 100.0,
            'rating' => 0,
        ]);
    }

    /**
     * Get best rated products
     */
    public function getBestRated(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0,
            'sales' => 0,
            'recent_views' => 0,
            'recent_sales' => 0,
            'wishlists' => 0,
            'rating' => 100.0,
        ]);
    }

    /**
     * Get most added to cart (using combined signals)
     */
    public function getMostAddedToCart(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 5.0,
            'sales' => 10.0,
            'recent_views' => 3.0,
            'recent_sales' => 0,
            'wishlists' => 2.0,
            'rating' => 0,
        ]);
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
        
        $categoryIds = $product->categories()->pluck('ec_product_categories.id')->toArray();
        
        if (empty($categoryIds)) {
            return collect();
        }
        
        // Use smart scoring for similar products from same category
        return $this->getSmartProducts($limit * 2, $categoryIds[0])
            ->where('id', '!=', $productId)
            ->filter(function($p) use ($priceMin, $priceMax) {
                return $p->front_sale_price >= $priceMin && $p->front_sale_price <= $priceMax;
            })
            ->take($limit);
    }

    /**
     * Apply vendor diversity to prevent showing too many products from one vendor
     */
    protected function applyVendorDiversity(Collection $products, int $limit): Collection
    {
        if (!is_plugin_active('marketplace')) {
            return $products->take($limit);
        }
        
        $result = collect();
        $vendorCounts = [];
        $maxPerVendor = max(3, ceil($limit / 3)); // Max 3 products per vendor or limit/3
        
        foreach ($products as $product) {
            $storeId = $product->store_id ?? 'no_store';
            $count = $vendorCounts[$storeId] ?? 0;
            
            if ($count < $maxPerVendor || $result->count() < $limit) {
                $result->push($product);
                $vendorCounts[$storeId] = $count + 1;
                
                if ($result->count() >= $limit) {
                    break;
                }
            }
        }
        
        return $result;
    }
}
