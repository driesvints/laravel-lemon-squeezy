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
        $fields = [
            'name' => $options['name'] ?? (string) $this->lemonSqueezyName(),
            'email' => $options['email'] ?? (string) $this->lemonSqueezyEmail(),
            'billing_address' => [
                'country' => $options['country'] ?? (string) $this->lemonSqueezyCountry(),
                'state' => $options['state'] ?? (string) $this->lemonSqueezyState(),
                'zip' => $options['zip'] ?? (string) $this->lemonSqueezyZip(),
            ],
            'tax_number' => $options['tax_number'] ?? (string) $this->lemonSqueezyTaxNumber(),
            'discount_code' => $options['discount_code'] ?? null,
        ];

        // We'll need a way to identify the user in any webhook we're catching so before
        // we make an API request we'll attach the authentication identifier to this
        // checkout so we can match it back to a user when handling Lemon Squeezy webhooks.
        $custom = [
            'billable_id' => $this->getKey(),
            'billable_type' => $this->getMorphClass(),
        ];

        return Checkout::make($this->getLemonSqueezyStore(), $variant)
            ->withPrefilledFields($fields)
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
