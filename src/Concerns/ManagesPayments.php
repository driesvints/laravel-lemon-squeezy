<?php

namespace LaravelLemonSqueezy\Concerns;

use LaravelLemonSqueezy\Checkout;

trait ManagesPayments
{
    /**
     * Create a new checkout instance to sell a product.
     */
    public function checkout(string $variant): Checkout
    {
        return Checkout::make($variant);
    }
}
