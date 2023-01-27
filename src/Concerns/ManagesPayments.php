<?php

namespace LaravelLemonSqueezy\Concerns;

use LaravelLemonSqueezy\Checkout;

trait ManagesPayments
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
        // we make the API request we'll attach the authentication identifier to this
        // checkout so we can match it back to a user when handling Lemon Squeezy webhooks.
        $data = [
            'billable_id' => $this->getKey(),
            'billable_type' => $this->getMorphClass(),
        ];

        return Checkout::make($variant)
            ->withPrefilledFields($fields)
            ->withCustomData($data);
    }
}
