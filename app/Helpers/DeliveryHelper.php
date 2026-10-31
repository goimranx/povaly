<?php

namespace App\Helpers;

use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Location\Models\Country;

class DeliveryHelper
{
    /**
     * Get estimated delivery days based on country with product-specific variance
     * 
     * @param string|null $countryCode Country code (e.g., 'BD', 'US', 'IN')
     * @param int|null $productId Optional product ID for consistent per-product variance
     * @return int Number of days for delivery
     */
    public static function getEstimatedDeliveryDays(?string $countryCode = null, ?int $productId = null): int
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
        
        // Add product-specific variance (simulates different warehouse locations, processing times)
        // If productId provided, use it for consistent variance per product
        if ($productId) {
            // Use product ID to generate consistent but varied delivery times
            // This simulates: different warehouse locations, stock availability, processing complexity
            $variance = ($productId % 5) - 2; // Range: -2 to +2 days
            
            // Occasionally add extra day for "special handling" products
            if ($productId % 7 === 0) {
                $variance += 1;
            }
            
            $finalDays = $baseDays + $variance;
        } else {
            // No product ID - use small random variance
            $variance = rand(-1, 2); // -1, 0, 1, or 2 days
            $finalDays = $baseDays + $variance;
        }
        
        // Ensure minimum 2 days delivery (even local)
        return max(2, $finalDays);
    }

    /**
     * Get estimated delivery date based on country and product
     * 
     * @param string|null $countryCode Country code
     * @param int|null $productId Optional product ID for consistent variance
     * @return \Carbon\Carbon
     */
    public static function getEstimatedDeliveryDate(?string $countryCode = null, ?int $productId = null): \Carbon\Carbon
    {
        $days = self::getEstimatedDeliveryDays($countryCode, $productId);
        
        // Skip weekends for delivery (Amazon-like behavior)
        $date = now();
        $daysAdded = 0;
        
        while ($daysAdded < $days) {
            $date = $date->addDay();
            // Skip Sundays (common delivery off day)
            if ($date->dayOfWeek !== 0) {
                $daysAdded++;
            }
        }
        
        return $date;
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
