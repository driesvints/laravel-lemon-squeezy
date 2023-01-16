<?php

namespace LaravelLemonSqueezy\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use LaravelLemonSqueezy\Customer;

trait ManagesCustomer
{
    /**
     * Create a customer record for the billable model.
     */
    public function createAsCustomer(array $attributes = []): Customer
    {
        return $this->customer()->create($attributes);
    }

    /**
     * Get the customer related to the billable model.
     */
    public function customer(): MorphOne
    {
        return $this->morphOne(Customer::class, 'billable');
    }

    /**
     * Get the billable model's email address to associate with Lemon Squeezy.
     */
    public function lemonSqueezyEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get the billable model's country to associate with Lemon Squeezy.
     *
     * This needs to be a 2 letter code.
     */
    public function lemonSqueezyCountry(): ?string
    {
        // return 'US';
    }
}
