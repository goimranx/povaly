<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Enums\CrossSellPriceType;
use Botble\Ecommerce\Models\Product;
use Closure;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;

class ProductCrossSalePriceService extends ProductPriceHandlerService
{
    protected array $appliedProducts = [];

    public function applyProduct(Product $product): void
    {
        $product->loadMissing('crossSales');

        if ($product->crossSales->isEmpty()) {
            return;
        }

        foreach ($product->crossSales as $crossSaleProduct) {
            $this->appliedProducts[$crossSaleProduct->getKey()] = $crossSaleProduct;

            if ($crossSaleProduct->variations()->exists()) {
                $crossSaleProduct->loadMissing('variations.product');
                $crossSaleProduct->variations->each(function ($variation): void {
                    $this->appliedProducts[$variation->product->getKey()] = $variation->product;
                });
            }
        }
    }

    public function applyProducts(Collection|array $products): void
    {
        foreach ($products as $product) {
            $this->applyProduct($product);
        }
    }

    public function getAppliedProducts(): array
    {
        return $this->appliedProducts;
    }

    public function handle(Product $product, Closure $next)
    {
        // IMPORTANT: Only apply cross-sell pricing if we're in a cross-sell display context
        // Don't apply cross-sell discounts globally to product cards in other sections
        if (empty($this->appliedProducts)) {
            return $next($product);
        }

        // Check if this is being called from a cross-sell/bundle context
        // by checking if the product has the 'pivot' relationship loaded WITH cross-sell data
        $originalProduct = $product->original_product;
        $crossSaleProduct = null;
        $crossSaleOriginalProduct = null;

        if ($originalProduct
            && key_exists($originalProductId = $originalProduct->getKey(), $this->appliedProducts)) {
            $crossSaleOriginalProduct = $this->appliedProducts[$originalProductId];
        }

        if (key_exists($productId = $product->getKey(), $this->appliedProducts)) {
            $crossSaleProduct = $this->appliedProducts[$productId];
        }

        if (! $crossSaleProduct && ! $crossSaleOriginalProduct) {
            return $next($product);
        }

        // SECURITY: Only apply cross-sell price if the product has the pivot loaded
        // This ensures we only apply discounts in the actual cross-sell display,
        // not in Store Products, Top Rated, or other regular product listings
        $pivot = null;

        if ($crossSaleProduct && $crossSaleProduct->relationLoaded('pivot') && $crossSaleProduct->pivot && $crossSaleProduct->pivot->price) {
            $pivot = $crossSaleProduct->pivot;
        } elseif ($crossSaleOriginalProduct && $crossSaleOriginalProduct->relationLoaded('pivot') && $crossSaleOriginalProduct->pivot && $crossSaleOriginalProduct->pivot->price) {
            $pivot = $crossSaleOriginalProduct->pivot;
        }

        if ($pivot) {
            $product = $this->calculateSalePrice($product, $pivot);
        }

        return $next($product);
    }

    protected function calculateSalePrice(Product $product, Pivot $pivot): Product
    {
        $price = (float) $pivot->price;
        $priceType = $pivot->price_type;
        $salePrice = $finalPrice = $product->getFinalPrice();

        if ($priceType == CrossSellPriceType::FIXED) {
            $salePrice = $finalPrice - $price;
        } elseif ($priceType == CrossSellPriceType::PERCENT) {
            $salePrice = $finalPrice - ($finalPrice * $price / 100);
        }

        if ($salePrice < $finalPrice) {
            $product->setFinalPrice($salePrice);
        }

        return $product;
    }
}
