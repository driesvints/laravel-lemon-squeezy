<?php

namespace LaravelLemonSqueezy\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use LaravelLemonSqueezy\LemonSqueezy;

trait ManagesSubscriptions
{
    /**
     * Get all of the subscriptions for the Billable model.
     */
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(LemonSqueezy::$subscriptionModel, 'billable')->orderByDesc('created_at');
    }
}
