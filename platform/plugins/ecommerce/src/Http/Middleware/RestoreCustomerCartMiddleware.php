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
            
            // RESTORE abandoned checkout items when user LEAVES checkout
            // Detect by checking if previous page (referrer) was checkout and current page is NOT
            $isCheckoutPage = $request->is('checkout/*') || $request->is('*/checkout/*');
            $referrer = $request->headers->get('referer');
            $cameFromCheckout = $referrer && (
                str_contains($referrer, '/checkout/') || 
                str_contains($referrer, '/checkout?')
            );
            
            // Only restore if:
            // 1. User came FROM checkout (referrer check)
            // 2. User is NOT on checkout now (navigated away)
            // 3. Not an AJAX request (to avoid interference)
            // 4. confirm_payment cart has items (checkout wasn't completed)
            $isAjaxRequest = $request->ajax() || $request->wantsJson();
            
            if ($cameFromCheckout && !$isCheckoutPage && !$isAjaxRequest) {
                $this->restoreAbandonedCheckout($cart, $request);
            }
            
        } catch (Throwable) {
            // Silently ignore during upgrade when customer_id column doesn't exist yet
        }

        return $next($request);
    }
    
    protected function restoreAbandonedCheckout($cart, $request): void
    {
        // Only restore if confirm_payment cart has items (checkout was abandoned)
        if (Cart::instance('confirm_payment')->isEmpty()) {
            return;
        }
        
        \Log::info('[Middleware] User left checkout - restoring abandoned items', [
            'referrer' => $request->headers->get('referer'),
            'current_url' => $request->url(),
            'confirm_payment_count' => Cart::instance('confirm_payment')->count(),
            'main_cart_count_before' => $cart->count()
        ]);
        
        $confirmPaymentItems = Cart::instance('confirm_payment')->content();
        
        foreach ($confirmPaymentItems as $item) {
            // Check if item already exists in main cart
            $existingInMainCart = $cart->search(function ($cartItem) use ($item) {
                return $cartItem->id == $item->id && $cartItem->options == $item->options;
            })->first();
            
            if (!$existingInMainCart) {
                // Item not in main cart - add it back
                $addedItem = $cart->add(
                    $item->id,
                    $item->name,
                    $item->qty,
                    $item->price,
                    $item->options->toArray()
                );
                
                // Associate model if available
                if (!empty($item->associatedModel) && is_string($item->associatedModel)) {
                    $addedItem->associate($item->associatedModel);
                }
                
                \Log::info('[Middleware] Restored abandoned item to main cart', [
                    'product_id' => $item->id,
                    'qty' => $item->qty
                ]);
            }
        }
        
        // Clear confirm_payment cart after restoration
        Cart::instance('confirm_payment')->destroy();
        
        // Persist the main cart
        if (auth('customer')->check()) {
            $cart->storeForCustomer(auth('customer')->id());
        } else {
            $guestIdentifier = $request->cookie('guest_cart_id');
            if ($guestIdentifier) {
                $cart->store($guestIdentifier);
            }
        }
        
        // Update cart timestamp to force global refresh
        $cart->setLastUpdatedAt(now());
        
        // Clear middleware cache to ensure fresh data
        session()->forget('cart_last_restored_at');
        
        \Log::info('[Middleware] Abandoned items restored and persisted globally', [
            'main_cart_count_after' => $cart->count()
        ]);
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
