<?php

namespace App\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Address;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;

class LocationSessionController extends BaseController
{
    /**
     * Save temporary location to session
     */
    public function saveTemporaryLocation(Request $request)
    {
        $type = $request->input('type'); // 'saved' or 'temporary'
        $data = $request->input('data');

        if ($type === 'saved' && isset($data['addressId'])) {
            // User selected a saved address
            session(['delivery_location_type' => 'saved']);
            session(['delivery_location_address_id' => $data['addressId']]);
            session()->forget('delivery_location_temp');
        } elseif ($type === 'temporary') {
            // User entered temporary address
            session(['delivery_location_type' => 'temporary']);
            session(['delivery_location_temp' => $data]);
            session()->forget('delivery_location_address_id');
        }

        return $this->httpResponse()->setMessage(__('Location saved successfully'));
    }

    /**
     * Get delivery display HTML
     */
    public function getDeliveryDisplay()
    {
        $html = '';
        $locationType = session('delivery_location_type');

        if ($locationType === 'saved') {
            $addressId = session('delivery_location_address_id');
            
            if ($addressId && auth('customer')->check()) {
                $address = Address::query()
                    ->where('id', $addressId)
                    ->where('customer_id', auth('customer')->id())
                    ->first();

                if ($address) {
                    $html = view(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                        'address' => $address,
                        'type' => 'saved'
                    ])->render();
                }
            }
        } elseif ($locationType === 'temporary') {
            $tempAddress = session('delivery_location_temp');
            
            if ($tempAddress) {
                $html = view(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                    'address' => (object) $tempAddress,
                    'type' => 'temporary'
                ])->render();
            }
        } else {
            // No location set, show default
            if (auth('customer')->check()) {
                $defaultAddress = auth('customer')->user()->addresses()->where('is_default', 1)->first();
                
                if ($defaultAddress) {
                    $html = view(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                        'address' => $defaultAddress,
                        'type' => 'default'
                    ])->render();
                } else {
                    $html = $this->getNoAddressHtml();
                }
            } else {
                $html = $this->getGuestNoAddressHtml();
            }
        }

        return $this->httpResponse()->setData(['html' => $html]);
    }

    /**
     * Get no address HTML for logged-in users
     */
    private function getNoAddressHtml()
    {
        return '<div class="delivery-no-address">' . __('No delivery address set') . '</div>' .
               '<a href="#" class="delivery-add-btn trigger-location-modal">' . __('Add address') . '</a>';
    }

    /**
     * Get no address HTML for guests
     */
    private function getGuestNoAddressHtml()
    {
        return '<div class="delivery-no-address">' . __('No delivery address set') . '</div>' .
               '<a href="#" class="delivery-add-btn trigger-location-modal">' . __('Login to add address') . '</a>';
    }

    /**
     * Clear temporary location from session
     */
    public function clearTemporaryLocation()
    {
        session()->forget(['delivery_location_type', 'delivery_location_address_id', 'delivery_location_temp']);

        return $this->httpResponse()->setMessage(__('Location cleared'));
    }

    /**
     * Get checkout address data from session
     * This merges temporary location data into checkout session
     */
    public static function getCheckoutAddressData()
    {
        $locationType = session('delivery_location_type');
        $data = [];

        if ($locationType === 'saved') {
            $addressId = session('delivery_location_address_id');
            
            if ($addressId && auth('customer')->check()) {
                $address = Address::query()
                    ->where('id', $addressId)
                    ->where('customer_id', auth('customer')->id())
                    ->first();

                if ($address) {
                    $data = [
                        'address_id' => $address->id,
                        'name' => $address->name,
                        'phone' => $address->phone,
                        'email' => $address->email,
                        'address' => $address->address,
                        'country' => $address->country,
                        'state' => $address->state,
                        'city' => $address->city,
                        'zip_code' => $address->zip_code,
                    ];
                }
            }
        } elseif ($locationType === 'temporary') {
            $tempAddress = session('delivery_location_temp');
            
            if ($tempAddress) {
                // For temporary addresses, we use the text names for display
                $data = [
                    'address' => $tempAddress['address'] ?? '',
                    'country' => $tempAddress['country'] ?? '',
                    'state' => $tempAddress['state'] ?? '', // This is the ID
                    'city' => $tempAddress['city'] ?? '', // This is the ID
                    'zip_code' => $tempAddress['zip_code'] ?? '',
                    // Store names for display purposes
                    'country_name' => $tempAddress['country_name'] ?? '',
                    'state_name' => $tempAddress['state_name'] ?? '',
                    'city_name' => $tempAddress['city_name'] ?? '',
                ];
            }
        }

        return $data;
    }
}
