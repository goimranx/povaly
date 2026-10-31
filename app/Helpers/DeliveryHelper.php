<?php

namespace App\Helpers;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Location\Models\Country;
use Botble\Ecommerce\Models\Product;

class DeliveryHelper
{
    /**
     * Amazon's ACTUAL formula: Delivery Date = Handling Time + Transit Time + Weekends/Holidays
     * 
     * HANDLING TIME: How long to prepare/pack the order (1-3 days typically)
     * TRANSIT TIME: Shipping duration based on destination country/region
     * 
     * This is the REAL way e-commerce platforms calculate delivery dates!
     */
    
    /**
     * Get handling time (order processing time) in days
     * This is how long it takes to prepare and pack the order before shipping
     * 
     * @param \Botble\Ecommerce\Models\Product|null $product
     * @return int Days needed to process the order
     */
    public static function getHandlingTime($product = null): int
    {
        // Default handling time: 1 day (standard processing)
        // This is typical for most e-commerce businesses
        $handlingTime = 1;
        
        // Load product if ID provided
        if (is_numeric($product)) {
            $product = Product::find($product);
        }
        
        if ($product && is_object($product)) {
            // Check stock availability
            if ($product->with_storehouse_management) {
                if ($product->quantity <= 0) {
                    // Out of stock: need to source/restock
                    $handlingTime = 3; // 3 days to source from supplier
                } elseif ($product->quantity < 5) {
                    // Low stock: might need warehouse transfer
                    $handlingTime = 2; // Extra day for warehouse coordination
                }
                // High stock (>5): standard 1 day handling
            }
            
            // If in flash sale, add extra processing time (high order volume)
            $inFlashSale = $product->latestFlashSales()->exists();
            if ($inFlashSale && $product->quantity > 0) {
                $handlingTime += 1; // Extra day due to high volume
            }
        }
        
        return $handlingTime;
    }
    
    /**
     * Get transit time (shipping duration) based on destination
     * This is the actual shipping time from warehouse to customer
     * 
     * @param string|null $countryCode Destination country code
     * @return int Days for shipping
     */
    public static function getTransitTime(?string $countryCode = null): int
    {
        if (!$countryCode) {
            return 5; // Default 5 days if no country
        }

        // Transit time estimates by destination (actual shipping days)
        // These are realistic shipping times for international e-commerce
        $transitTimes = [
            // Local/Domestic (1-2 days)
            'BD' => 2,  // Bangladesh (local/domestic)
            
            // Regional neighbors (2-3 days)
            'IN' => 3,  // India
            'PK' => 3,  // Pakistan
            'LK' => 3,  // Sri Lanka
            'NP' => 3,  // Nepal
            'MM' => 3,  // Myanmar
            'BT' => 3,  // Bhutan
            
            // Asia region (3-5 days)
            'TH' => 4,  // Thailand
            'MY' => 4,  // Malaysia
            'SG' => 4,  // Singapore
            'ID' => 4,  // Indonesia
            'VN' => 4,  // Vietnam
            'PH' => 4,  // Philippines
            'CN' => 5,  // China
            'JP' => 5,  // Japan
            'KR' => 5,  // South Korea
            'HK' => 4,  // Hong Kong
            'TW' => 4,  // Taiwan
            
            // Middle East (4-6 days)
            'AE' => 5,  // UAE
            'SA' => 6,  // Saudi Arabia
            'QA' => 5,  // Qatar
            'KW' => 5,  // Kuwait
            'OM' => 6,  // Oman
            'BH' => 5,  // Bahrain
            'TR' => 6,  // Turkey
            
            // Europe (5-8 days)
            'GB' => 7,  // United Kingdom
            'DE' => 7,  // Germany
            'FR' => 7,  // France
            'IT' => 8,  // Italy
            'ES' => 8,  // Spain
            'NL' => 7,  // Netherlands
            'BE' => 7,  // Belgium
            'CH' => 7,  // Switzerland
            'AT' => 7,  // Austria
            'SE' => 8,  // Sweden
            'NO' => 8,  // Norway
            'DK' => 7,  // Denmark
            'PL' => 8,  // Poland
            'RU' => 9,  // Russia
            
            // North America (7-10 days)
            'US' => 9,  // United States
            'CA' => 9,  // Canada
            'MX' => 10, // Mexico
            
            // South America (9-12 days)
            'BR' => 11, // Brazil
            'AR' => 11, // Argentina
            'CL' => 12, // Chile
            'CO' => 11, // Colombia
            'PE' => 12, // Peru
            
            // Oceania (8-10 days)
            'AU' => 9,  // Australia
            'NZ' => 10, // New Zealand
            
            // Africa (8-12 days)
            'ZA' => 10, // South Africa
            'EG' => 9,  // Egypt
            'NG' => 11, // Nigeria
            'KE' => 10, // Kenya
            'GH' => 11, // Ghana
        ];

        return $transitTimes[$countryCode] ?? 5; // Default 5 days for unlisted countries
    }
    
    /**
     * Calculate total delivery days using Amazon's formula
     * 
     * @param string|null $countryCode Destination country
     * @param \Botble\Ecommerce\Models\Product|int|null $product Product or product ID
     * @return int Total delivery days
     */
    public static function getEstimatedDeliveryDays(?string $countryCode = null, $product = null): int
    {
        // Amazon's formula: Handling Time + Transit Time
        $handlingTime = self::getHandlingTime($product);
        $transitTime = self::getTransitTime($countryCode);
        
        $totalDays = $handlingTime + $transitTime;
        
        // Minimum 2 days (even express local delivery)
        return max(2, $totalDays);
    }

    /**
     * Get estimated delivery date
     * 
     * @param string|null $countryCode Country code
     * @param \Botble\Ecommerce\Models\Product|int|null $product Product model or ID
     * @return \Carbon\Carbon
     */
    public static function getEstimatedDeliveryDate(?string $countryCode = null, $product = null): \Carbon\Carbon
    {
        $days = self::getEstimatedDeliveryDays($countryCode, $product);
        
        // Add calendar days (Amazon counts calendar days, not business days)
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
     * Get delivery date range (for showing delivery window)
     * 
     * @param string|null $countryCode
     * @param \Botble\Ecommerce\Models\Product|int|null $product
     * @return array [minDate, maxDate]
     */
    public static function getDeliveryDateRange(?string $countryCode = null, $product = null): array
    {
        $estimatedDays = self::getEstimatedDeliveryDays($countryCode, $product);
        
        // Provide a 1-2 day buffer window
        $minDays = max(2, $estimatedDays - 1);
        $maxDays = $estimatedDays + 1;
        
        return [
            'min' => now()->addDays($minDays),
            'max' => now()->addDays($maxDays),
            'estimated' => now()->addDays($estimatedDays),
        ];
    }
}
