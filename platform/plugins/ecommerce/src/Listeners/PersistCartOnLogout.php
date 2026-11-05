<?php

namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Auth\Events\Logout;

class PersistCartOnLogout
{
    public function handle(Logout $event): void
    {
        if (! $event->user instanceof Customer) {
            return;
        }

        $customer = $event->user;

        // Save main cart
        if (Cart::instance('cart')->isNotEmpty()) {
            Cart::instance('cart')->storeForCustomerQuietly($customer->id);
        }
        
        // Save confirm_payment cart
        if (Cart::instance('confirm_payment')->isNotEmpty()) {
            Cart::instance('confirm_payment')->storeForCustomerQuietly($customer->id);
        }
    }
}
