<?php

namespace App\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Supports\OrderHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;

class LocationSessionController extends BaseController
{
    /**
     * Save location to checkout session (integrates with existing checkout system)
     */
    public function saveTemporaryLocation(Request $request)
    {
        $type = $request->input('type'); // 'saved' or 'temporary'
        $data = $request->input('data');
        
        $orderHelper = app(OrderHelper::class);
        $token = $orderHelper->getOrderSessionToken();
        
        $sessionData = [];

        if ($type === 'saved' && isset($data['addressId'])) {
            // User selected a saved address - store address_id in session
            $addressId = $data['addressId'];
            
            if (auth('customer')->check()) {
                $address = Address::query()
                    ->where('id', $addressId)
                    ->where('customer_id', auth('customer')->id())
                    ->first();
                
                if ($address) {
                    // Store address_id so OrderHelper can load it
                    $sessionData = [
                        'address_id' => $address->id,
                    ];
                }
            }
        } elseif ($type === 'temporary') {
            // User entered temporary address - store all fields in session
            $sessionData = [
                'address_id' => 'new', // Indicates new/temporary address
                'name' => $data['name'] ?? '',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? (auth('customer')->check() ? auth('customer')->user()->email : ''),
                'country' => $data['country'] ?? '',
                'state' => $data['state'] ?? '',
                'city' => $data['city'] ?? '',
                'address' => $data['address'] ?? '',
                'zip_code' => $data['zip_code'] ?? '',
            ];
        }

        // Save to checkout session using OrderHelper
        if (!empty($sessionData)) {
            $orderHelper->setOrderSessionData($token, $sessionData);
        }

        return $this->httpResponse()->setMessage(__('Location saved successfully'));
    }

    /**
     * Get delivery display HTML from checkout session
     */
    public function getDeliveryDisplay(Request $request)
    {
        $html = '';
        $productId = $request->input('product_id'); // Optional product ID for per-product estimates
        
        $orderHelper = app(OrderHelper::class);
        $token = $orderHelper->getOrderSessionToken();
        $sessionData = $orderHelper->getOrderSessionData($token);
        
        $addressId = $sessionData['address_id'] ?? null;
        
        // Get product if ID provided
        $product = null;
        if ($productId) {
            $product = \Botble\Ecommerce\Models\Product::find($productId);
        }
        
        if ($addressId && $addressId !== 'new') {
            // Saved address from address book
            if (auth('customer')->check()) {
                $address = Address::query()
                    ->where('id', $addressId)
                    ->where('customer_id', auth('customer')->id())
                    ->first();
                
                if ($address) {
                    $html = view(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                        'address' => $address,
                        'type' => 'saved',
                        'product' => $product
                    ])->render();
                } else {
                    $html = $this->getDefaultAddressHtml($product);
                }
            } else {
                $html = $this->getDefaultAddressHtml($product);
            }
        } elseif ($addressId === 'new' && !empty($sessionData['country'])) {
            // Temporary address entered in modal
            $html = view(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                'address' => (object) $sessionData,
                'type' => 'temporary',
                'product' => $product
            ])->render();
        } else {
            // No address set yet, show default
            $html = $this->getDefaultAddressHtml($product);
        }

        return $this->httpResponse()->setData(['html' => $html]);
    }

    /**
     * Get default address HTML (shows default saved address or "no address" message)
     */
    private function getDefaultAddressHtml($product = null)
    {
        if (auth('customer')->check()) {
            $defaultAddress = auth('customer')->user()->addresses()->where('is_default', 1)->first();
            
            if ($defaultAddress) {
                return view(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                    'address' => $defaultAddress,
                    'type' => 'default',
                    'product' => $product
                ])->render();
            } else {
                return '<div class="delivery-no-address">' . __('No delivery address set') . '</div>' .
                       '<a href="#" class="delivery-add-btn trigger-location-modal">' . __('Add address') . '</a>';
            }
        } else {
            return '<div class="delivery-no-address">' . __('No delivery address set') . '</div>' .
                   '<a href="#" class="delivery-add-btn trigger-location-modal">' . __('Select location') . '</a>';
        }
    }

    /**
     * Clear location from session
     */
    public function clearTemporaryLocation()
    {
        $orderHelper = app(OrderHelper::class);
        $token = $orderHelper->getOrderSessionToken();
        
        // Clear address-related fields from checkout session
        $sessionData = $orderHelper->getOrderSessionData($token);
        unset(
            $sessionData['address_id'],
            $sessionData['name'],
            $sessionData['phone'],
            $sessionData['email'],
            $sessionData['country'],
            $sessionData['state'],
            $sessionData['city'],
            $sessionData['address'],
            $sessionData['zip_code']
        );
        
        $orderHelper->setOrderSessionData($token, $sessionData);

        return $this->httpResponse()->setMessage(__('Location cleared'));
    }
}
