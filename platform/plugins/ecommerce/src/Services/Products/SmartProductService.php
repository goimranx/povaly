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
     * Get smart products with combined scoring from multiple criteria.
     *
     * Uses a single raw SQL query that wraps ec_products in a subquery first,
     * so all columns are pre-selected before aggregation — no ONLY_FULL_GROUP_BY issues.
     */
    public function getSmartProducts(int $limit, ?int $categoryId = null, array $weights = []): Collection
    {
        $defaultWeights = [
            'views'         => 1.0,
            'sales'         => 10.0,
            'recent_views'  => 2.0,
            'recent_sales'  => 15.0,
            'wishlists'     => 5.0,
            'rating'        => 3.0,
        ];

        $weights = array_merge($defaultWeights, $weights);
        $daysAgo = now()->subDays(7)->format('Y-m-d H:i:s');

        // Category join fragment
        $categoryJoin = '';
        $categoryWhere = '';
        if ($categoryId) {
            $categoryJoin  = "INNER JOIN ec_product_categories epc ON epc.product_id = p.id";
            $categoryWhere = "AND epc.category_id = " . (int)$categoryId;
        }

        // Marketplace join fragment
        $marketplaceJoin  = '';
        $marketplaceWhere = '';
        if (is_plugin_active('marketplace')) {
            $marketplaceJoin  = "LEFT JOIN mp_stores ms ON ms.id = p.store_id";
            $marketplaceWhere = "AND (ms.status = 'published' OR ms.id IS NULL)";
        }

        // Score formula
        $scoreExpr = sprintf(
            '(COALESCE(p.views, 0) * %f)
           + (COALESCE(s.total_sold, 0) * %f)
           + (COALESCE(rv.recent_view_count, 0) * %f)
           + (COALESCE(rs.recent_sold, 0) * %f)
           + (COALESCE(wl.wishlist_count, 0) * %f)
           + (COALESCE(p.reviews_avg * LOG(COALESCE(p.reviews_count, 0) + 1), 0) * %f)',
            $weights['views'],
            $weights['sales'],
            $weights['recent_views'],
            $weights['recent_sales'],
            $weights['wishlists'],
            $weights['rating']
        );

        // Single clean raw SQL — ec_products aliased as p, no outer GROUP BY on p columns
        $sql = "
            SELECT p.id, ({$scoreExpr}) AS smart_score
            FROM ec_products p
            {$marketplaceJoin}
            {$categoryJoin}
            LEFT JOIN (
                SELECT op.product_id, SUM(op.qty) AS total_sold
                FROM ec_order_product op
                INNER JOIN ec_orders o ON o.id = op.order_id
                WHERE o.status = 'completed'
                GROUP BY op.product_id
            ) s ON s.product_id = p.id
            LEFT JOIN (
                SELECT op.product_id, SUM(op.qty) AS recent_sold
                FROM ec_order_product op
                INNER JOIN ec_orders o ON o.id = op.order_id
                WHERE o.status = 'completed'
                  AND o.created_at >= '{$daysAgo}'
                GROUP BY op.product_id
            ) rs ON rs.product_id = p.id
            LEFT JOIN (
                SELECT product_id, COUNT(*) AS wishlist_count
                FROM ec_wish_lists
                GROUP BY product_id
            ) wl ON wl.product_id = p.id
            LEFT JOIN (
                SELECT product_id, COUNT(*) AS recent_view_count
                FROM ec_product_views
                WHERE date >= '{$daysAgo}'
                GROUP BY product_id
            ) rv ON rv.product_id = p.id
            WHERE p.status = 'published'
              AND p.is_variation = 0
              {$marketplaceWhere}
              {$categoryWhere}
            ORDER BY smart_score DESC
            LIMIT " . ($limit * 2) . "
        ";

        $rows = DB::select($sql);

        $productIds = array_column($rows, 'id');

        if (empty($productIds)) {
            return collect();
        }

        // Eager-load products preserving score order
        $products = $this->productRepository
            ->getModel()
            ->whereIn('id', $productIds)
            ->with(EcommerceHelper::withProductEagerLoadingRelations())
            ->get();

        $orderedProducts = collect($productIds)
            ->map(fn($id) => $products->firstWhere('id', $id))
            ->filter();

        return $this->applyVendorDiversity($orderedProducts, $limit);
    }

    public function getMostViewed(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 10.0, 'sales' => 0, 'recent_views' => 0,
            'recent_sales' => 0, 'wishlists' => 0, 'rating' => 0,
        ]);
    }

    public function getMostSold(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0, 'sales' => 100.0, 'recent_views' => 0,
            'recent_sales' => 0, 'wishlists' => 0, 'rating' => 0,
        ]);
    }

    public function getTrending(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0.5, 'sales' => 2.0, 'recent_views' => 10.0,
            'recent_sales' => 50.0, 'wishlists' => 1.0, 'rating' => 1.0,
        ]);
    }

    public function getMostWishlisted(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0, 'sales' => 0, 'recent_views' => 0,
            'recent_sales' => 0, 'wishlists' => 100.0, 'rating' => 0,
        ]);
    }

    public function getBestRated(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 0, 'sales' => 0, 'recent_views' => 0,
            'recent_sales' => 0, 'wishlists' => 0, 'rating' => 100.0,
        ]);
    }

    public function getMostAddedToCart(int $limit, ?int $categoryId = null): Collection
    {
        return $this->getSmartProducts($limit, $categoryId, [
            'views' => 5.0, 'sales' => 10.0, 'recent_views' => 3.0,
            'recent_sales' => 0, 'wishlists' => 2.0, 'rating' => 0,
        ]);
    }

    public function getSimilarProducts(int $productId, int $limit): Collection
    {
        $product = $this->productRepository->findById($productId);
        if (!$product) return collect();

        $priceMin    = $product->front_sale_price * 0.8;
        $priceMax    = $product->front_sale_price * 1.2;
        $categoryIds = $product->categories()->pluck('ec_product_categories.id')->toArray();

        if (empty($categoryIds)) return collect();

        return $this->getSmartProducts($limit * 2, $categoryIds[0])
            ->where('id', '!=', $productId)
            ->filter(fn($p) => $p->front_sale_price >= $priceMin && $p->front_sale_price <= $priceMax)
            ->take($limit);
    }

    protected function applyVendorDiversity(Collection $products, int $limit): Collection
    {
        if (!is_plugin_active('marketplace')) {
            return $products->take($limit);
        }

        $result       = collect();
        $vendorCounts = [];
        $maxPerVendor = max(3, (int)ceil($limit / 3));

        foreach ($products as $product) {
            $storeId = $product->store_id ?? 'no_store';
            $count   = $vendorCounts[$storeId] ?? 0;

            if ($count < $maxPerVendor) {
                $result->push($product);
                $vendorCounts[$storeId] = $count + 1;
                if ($result->count() >= $limit) break;
            }
        }

        return $result;
    }
}
