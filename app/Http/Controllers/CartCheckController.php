<?php

namespace App\Http\Controllers;

use Botble\Ecommerce\Facades\Cart;
use Illuminate\Http\Request;

class CartCheckController extends Controller
{
    /**
     * Check if a product is in the cart
     */
    public function check(Request $request, int $productId)
    {
        $cartItems = Cart::instance('cart')->content();
        
        $inCart = false;
        $quantity = 0;
        
        foreach ($cartItems as $item) {
            if ($item->id == $productId) {
                $inCart = true;
                $quantity += $item->qty;
            }
        }
        
        return response()->json([
            'success' => true,
            'in_cart' => $inCart,
            'quantity' => $quantity,
        ]);
    }
}
