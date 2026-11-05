<?php

namespace Botble\Ecommerce\Http\Middleware;

use Botble\Ecommerce\Facades\Cart;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class RestoreCustomerCartMiddleware
{
    protected int $cacheTtl = 30;

    protected int $guestCartCookieDays = 30;

    public function handle(Request $request, Closure $next)
    {
        try {
            $cart = Cart::instance('cart');

            // Skip restoration if we're in checkout with selected items (cart was already filtered)
            if (session()->has('checkout_original_cart')) {
                \Log::info('RestoreCustomerCartMiddleware: Skipping restoration - checkout_original_cart exists');
                return $next($request);
            }

            \Log::info('RestoreCustomerCartMiddleware: Processing restoration', ['has_customer' => auth('customer')->check()]);

            if (auth('customer')->check()) {
                $this->handleCustomerCart($cart);
            } else {
                $this->handleGuestCart($request, $cart);
            }
        } catch (Throwable) {
            // Silently ignore during upgrade when customer_id column doesn't exist yet
        }

        return $next($request);
    }

    protected function handleCustomerCart($cart): void
    {
        $customerId = auth('customer')->id();

        $lastRestoredAt = session('cart_last_restored_at');
        $shouldRestore = ! $lastRestoredAt || Carbon::parse($lastRestoredAt)->addSeconds($this->cacheTtl)->isPast();

        if ($shouldRestore) {
            $dbUpdatedAt = $cart->getCustomerCartUpdatedAt($customerId);

            if ($dbUpdatedAt) {
                $sessionUpdatedAt = $cart->getLastUpdatedAt();

                if (! $sessionUpdatedAt || $dbUpdatedAt->gt($sessionUpdatedAt)) {
                    $cart->restoreForCustomerQuietly($customerId);
                }
            } elseif ($cart->isNotEmpty()) {
                $cart->destroy();
            }
            
            // Also restore confirm_payment cart for logged-in users
            $confirmPaymentCart = Cart::instance('confirm_payment');
            $confirmPaymentDbUpdatedAt = $confirmPaymentCart->getCustomerCartUpdatedAt($customerId);
            
            if ($confirmPaymentDbUpdatedAt) {
                $confirmPaymentSessionUpdatedAt = $confirmPaymentCart->getLastUpdatedAt();
                
                if (! $confirmPaymentSessionUpdatedAt || $confirmPaymentDbUpdatedAt->gt($confirmPaymentSessionUpdatedAt)) {
                    $confirmPaymentCart->restoreForCustomerQuietly($customerId);
                }
            }

            session(['cart_last_restored_at' => Carbon::now()->toIso8601String()]);
        }
    }

    protected function handleGuestCart(Request $request, $cart): void
    {
        $guestIdentifier = $request->cookie('guest_cart_id');

        if (! $guestIdentifier) {
            return;
        }

        $lastRestoredAt = session('cart_last_restored_at');
        $shouldRestore = ! $lastRestoredAt || Carbon::parse($lastRestoredAt)->addSeconds($this->cacheTtl)->isPast();

        if ($shouldRestore) {
            $dbUpdatedAt = $cart->getGuestCartUpdatedAt($guestIdentifier);

            if ($dbUpdatedAt) {
                $sessionUpdatedAt = $cart->getLastUpdatedAt();

                if (! $sessionUpdatedAt || $dbUpdatedAt->gt($sessionUpdatedAt)) {
                    $cart->restoreGuestCartQuietly($guestIdentifier);
                }
            }

            session(['cart_last_restored_at' => Carbon::now()->toIso8601String()]);
        }
    }

    public static function getOrCreateGuestIdentifier(): string
    {
        $identifier = request()->cookie('guest_cart_id');

        if (! $identifier) {
            $identifier = (string) Str::uuid();
            cookie()->queue('guest_cart_id', $identifier, 60 * 24 * 30);
        }

        return $identifier;
    }
}
