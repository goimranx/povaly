<?php

namespace App\Helpers;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Location\Models\Country;

class DeliveryHelper
{
    /**
     * Get estimated delivery days based on country with REAL product factors
     * 
     * @param string|null $countryCode Country code (e.g., 'BD', 'US', 'IN')
     * @param \Botble\Ecommerce\Models\Product|int|null $product Product model or ID
     * @return int Number of days for delivery
     */
    public static function getEstimatedDeliveryDays(?string $countryCode = null, $product = null): int
    {
        if (!$countryCode) {
            return 7; // Default 7 days if no country
        }

        // Base delivery estimates by country (in days)
        $deliveryEstimates = [
            // Local/Fast countries (2-4 days)
            'BD' => 3,  // Bangladesh (local)
            'IN' => 4,  // India (nearby)
            'PK' => 4,  // Pakistan (nearby)
            'LK' => 4,  // Sri Lanka (nearby)
            'NP' => 4,  // Nepal (nearby)
            
            // Asia region (5-7 days)
            'CN' => 6,  // China
            'TH' => 5,  // Thailand
            'MY' => 5,  // Malaysia
            'SG' => 5,  // Singapore
            'ID' => 6,  // Indonesia
            'VN' => 6,  // Vietnam
            'PH' => 6,  // Philippines
            'JP' => 7,  // Japan
            'KR' => 7,  // South Korea
            'AE' => 6,  // UAE
            'SA' => 7,  // Saudi Arabia
            'QA' => 6,  // Qatar
            
            // Europe (7-10 days)
            'GB' => 8,  // United Kingdom
            'DE' => 8,  // Germany
            'FR' => 8,  // France
            'IT' => 9,  // Italy
            'ES' => 9,  // Spain
            'NL' => 8,  // Netherlands
            'BE' => 8,  // Belgium
            'CH' => 8,  // Switzerland
            'AT' => 8,  // Austria
            'SE' => 9,  // Sweden
            'NO' => 9,  // Norway
            'DK' => 8,  // Denmark
            'PL' => 9,  // Poland
            'RU' => 10, // Russia
            
            // North America (8-12 days)
            'US' => 10, // United States
            'CA' => 10, // Canada
            'MX' => 11, // Mexico
            
            // South America (10-14 days)
            'BR' => 12, // Brazil
            'AR' => 12, // Argentina
            'CL' => 13, // Chile
            'CO' => 12, // Colombia
            'PE' => 13, // Peru
            
            // Oceania (9-12 days)
            'AU' => 10, // Australia
            'NZ' => 11, // New Zealand
            
            // Africa (10-14 days)
            'ZA' => 11, // South Africa
            'EG' => 10, // Egypt
            'NG' => 12, // Nigeria
            'KE' => 11, // Kenya
        ];

        $baseDays = $deliveryEstimates[$countryCode] ?? 7;
        $additionalDays = 0;
        
        // Load product if ID provided
        if (is_numeric($product)) {
            $product = \Botble\Ecommerce\Models\Product::find($product);
        }
        
        // Calculate based on REAL product attributes
        if ($product && is_object($product)) {
            
            // 1. WEIGHT FACTOR: Heavier items take longer (real shipping constraint)
            if ($product->weight) {
                if ($product->weight > 20) {
                    $additionalDays += 2; // Very heavy (>20kg) - special handling
                } elseif ($product->weight > 10) {
                    $additionalDays += 1; // Heavy (>10kg)
                } elseif ($product->weight < 1) {
                    $additionalDays -= 1; // Light items ship faster
                }
            }
            
            // 2. STOCK AVAILABILITY: Out of stock or low stock takes longer
            if ($product->with_storehouse_management) {
                if ($product->quantity <= 0) {
                    $additionalDays += 3; // Out of stock - needs restocking/sourcing
                } elseif ($product->quantity < 5) {
                    $additionalDays += 1; // Low stock - may need sourcing from warehouse
                } elseif ($product->quantity > 50) {
                    $additionalDays -= 1; // High stock - readily available, faster processing
                }
            }
            
            // 3. STORE/VENDOR LOCATION: Different stores = different warehouses
            if (is_plugin_active('marketplace') && $product->store_id) {
                // Use store_id to create consistent variance per store
                $storeVariance = ($product->store_id % 3) - 1; // -1, 0, or +1 days
                $additionalDays += $storeVariance;
            }
            
            // 4. PRODUCT SIZE/DIMENSIONS: Bulky items need special handling
            if ($product->length || $product->wide || $product->height) {
                $volume = ($product->length ?? 0) * ($product->wide ?? 0) * ($product->height ?? 0);
                if ($volume > 1000000) { // Very bulky (>1 cubic meter)
                    $additionalDays += 2;
                } elseif ($volume > 100000) { // Bulky
                    $additionalDays += 1;
                }
            }
            
            // 5. FEATURED/PRIORITY ITEMS: Featured items often ship faster
            if ($product->is_featured) {
                $additionalDays -= 1; // Priority handling
            }
            
            // 6. FLASH SALE ITEMS: High demand can cause delays
            $inFlashSale = $product->latestFlashSales()->exists();
            if ($inFlashSale) {
                $additionalDays += 1; // High order volume during flash sales
            }
            
        } else {
            // Fallback: use simple product ID variance if no product object
            $productId = is_numeric($product) ? $product : null;
            if ($productId) {
                $additionalDays = ($productId % 5) - 2; // -2 to +2 days
                if ($productId % 7 === 0) {
                    $additionalDays += 1;
                }
            }
        }
        
        $finalDays = $baseDays + $additionalDays;
        
        // Ensure minimum 2 days delivery (even express)
        return max(2, $finalDays);
    }

    /**
     * Get estimated delivery date based on country and product
     * 
     * @param string|null $countryCode Country code
     * @param \Botble\Ecommerce\Models\Product|int|null $product Product model or ID
     * @return \Carbon\Carbon
     */
    public static function getEstimatedDeliveryDate(?string $countryCode = null, $product = null): \Carbon\Carbon
    {
        $days = self::getEstimatedDeliveryDays($countryCode, $product);
        
        // Simply add calendar days
        return now()->addDays($days);
    }

    /**
     * Get country code from country ID
     * 
     * @param int|string|null $countryId
     * @return string|null
     */
    public static function getCountryCodeById($countryId): ?string
    {
        if (!$countryId) {
            return null;
        }

        $country = Country::find($countryId);
        return $country ? $country->code : null;
    }

    /**
     * Format delivery date for display
     * 
     * @param \Carbon\Carbon $date
     * @return string
     */
    public static function formatDeliveryDate(\Carbon\Carbon $date): string
    {
        // Format as "Monday, November 10"
        return $date->format('l, F j');
    }

    /**
     * Get delivery date range (min-max days)
     * 
     * @param string|null $countryCode
     * @return array [minDate, maxDate]
     */
    public static function getDeliveryDateRange(?string $countryCode = null): array
    {
        $baseDays = self::getEstimatedDeliveryDays($countryCode);
        
        // Add a buffer of ±1-2 days for range
        $minDays = max(2, $baseDays - 1);
        $maxDays = $baseDays + 2;
        
        return [
            'min' => now()->addDays($minDays),
            'max' => now()->addDays($maxDays),
        ];
    }
}
