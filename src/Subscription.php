<?php

namespace LaravelLemonSqueezy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property \LaravelLemonSqueezy\Billable $billable
 */
class Subscription extends Model
{
    const STATUS_ON_TRIAL = 'on_trial';

    const STATUS_ACTIVE = 'active';

    const STATUS_PAUSED = 'paused';

    const STATUS_PAST_DUE = 'past_due';

    const STATUS_UNPAID = 'unpaid';

    const STATUS_CANCELLED = 'cancelled';

    const STATUS_EXPIRED = 'expired';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lemon_squeezy_subscriptions';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'trial_ends_at' => 'datetime',
        'paused_from' => 'datetime',
        'renews_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the billable model related to the subscription.
     */
    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}
