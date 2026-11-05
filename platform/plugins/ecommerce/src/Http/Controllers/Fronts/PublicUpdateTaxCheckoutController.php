<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Services\HandleCheckoutOrderData;
use Botble\Ecommerce\Services\HandleTaxService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PublicUpdateTaxCheckoutController extends BaseController
{
    public function __invoke(Request $request, HandleCheckoutOrderData $handleCheckoutOrderData)
    {
        $sessionCheckoutData = OrderHelper::getOrderSessionData(
            $token = OrderHelper::getOrderSessionToken()
        );

        if ($country = $request->input('address.country')) {
            if (Arr::get($sessionCheckoutData, 'country') !== $country) {
                $sessionCheckoutData['country'] = $country;
                OrderHelper::setOrderSessionData($token, $sessionCheckoutData);
            }
        }

        /**
         * @var Collection $products
         */
        // Check if we're in a checkout with selected items
        // Check if checkout cart has items (more reliable than session check)
        $checkoutCartCount = Cart::instance('checkout')->count();
        $hasSelectedItems = $checkoutCartCount > 0;
        
        \Log::info('AJAX Tax Update: Determining cart instance', [
            'checkout_cart_count' => $checkoutCartCount,
            'has_selected_items' => $hasSelectedItems,
            'session_has_key' => session()->has('selected_cart_items'),
            'session_value' => session()->get('selected_cart_items', [])
        ]);
        
        if ($hasSelectedItems) {
            // Use checkout cart instance when items were selected
            $products = Cart::instance('checkout')->products();
            \Log::info('AJAX Tax Update: Using checkout cart instance', [
                'products_count' => $products->count()
            ]);
        } else {
            // Use main cart when all items are being checked out
            $products = Cart::instance('cart')->products();
            \Log::info('AJAX Tax Update: Using main cart instance', [
                'products_count' => $products->count()
            ]);
        }

        $checkoutOrderData = $handleCheckoutOrderData->execute(
            $request,
            $products,
            $token,
            $sessionCheckoutData
        );

        app(HandleTaxService::class)->execute($products, [
            ...$sessionCheckoutData,
            'country' => $request->input('address.country'),
            'state' => $request->input('address.state'),
            'city' => $request->input('address.city'),
            'zip_code' => $request->input('address.zip_code'),
        ]);

        return $this
            ->httpResponse()
            ->setData([
                'amount' => view('plugins/ecommerce::orders.partials.amount', [
                    'products' => $products,
                    'rawTotal' => $checkoutOrderData->rawTotal,
                    'orderAmount' => $checkoutOrderData->orderAmount,
                    'shipping' => $checkoutOrderData->shipping,
                    'sessionCheckoutData' => $sessionCheckoutData,
                    'shippingAmount' => $checkoutOrderData->shippingAmount,
                    'promotionDiscountAmount' => $checkoutOrderData->promotionDiscountAmount,
                    'couponDiscountAmount' => $checkoutOrderData->couponDiscountAmount,
                    'paymentFee' => $checkoutOrderData->paymentFee,
                    'shippingTaxAmount' => $checkoutOrderData->shippingTaxAmount,
                ])->render(),
            ]);
    }
}
