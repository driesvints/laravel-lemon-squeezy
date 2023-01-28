<?php

namespace LaravelLemonSqueezy\Concerns;

use LaravelLemonSqueezy\Checkout;
use LaravelLemonSqueezy\Exceptions\MissingStoreException;

trait ManagesCheckouts
{
    /**
     * Create a new checkout instance to sell a product.
     */
    public function checkout(string $variant, array $options = []): Checkout
    {
        // We'll need a way to identify the user in any webhook we're catching so before
        // we make an API request we'll attach the authentication identifier to this
        // checkout so we can match it back to a user when handling Lemon Squeezy webhooks.
        $custom = [
            'billable_id' => $this->getKey(),
            'billable_type' => $this->getMorphClass(),
        ];

        return Checkout::make($this->getLemonSqueezyStore(), $variant)
            ->withName($options['name'] ?? (string) $this->lemonSqueezyName())
            ->withEmail($options['email'] ?? (string) $this->lemonSqueezyEmail())
            ->withBillingAddress(
                $options['country'] ?? (string) $this->lemonSqueezyCountry(),
                $options['state'] ?? (string) $this->lemonSqueezyState(),
                $options['zip'] ?? (string) $this->lemonSqueezyZip(),
            )
            ->withTaxNumber($options['tax_number'] ?? (string) $this->lemonSqueezyTaxNumber())
            ->withDiscountCode($options['discount_code'] ?? '')
            ->withCustomData($custom);
    }

    /**
     * Get the configured Lemon Squeezy store subdomain from the config.
     *
     * @throws MissingStoreException
     */
    public function getLemonSqueezyStore(): string
    {
        if (! $store = config('lemon-squeezy.store')) {
            throw MissingStoreException::notConfigured();
        }

        return $store;
    }
}
