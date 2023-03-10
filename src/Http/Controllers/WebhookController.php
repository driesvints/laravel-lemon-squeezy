<?php

namespace LaravelLemonSqueezy\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use LaravelLemonSqueezy\Events\SubscriptionCreated;
use LaravelLemonSqueezy\Events\WebhookHandled;
use LaravelLemonSqueezy\Events\WebhookReceived;
use LaravelLemonSqueezy\Exceptions\InvalidCustomPayload;
use LaravelLemonSqueezy\Http\Middleware\VerifyWebhookSignature;
use LaravelLemonSqueezy\LemonSqueezy;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    public function __construct()
    {
        if (config('lemon-squeezy.signing_secret')) {
            $this->middleware(VerifyWebhookSignature::class);
        }
    }

    /**
     * Handle a Lemon Squeezy webhook call.
     */
    public function __invoke(Request $request): Response
    {
        $payload = $request->all();

        if (! isset($payload['meta']['event_name'])) {
            return new Response('Webhook received but no event name was found.');
        }

        $method = 'handle'.Str::studly($payload['meta']['event_name']);

        WebhookReceived::dispatch($payload);

        if (method_exists($this, $method)) {
            try {
                $this->{$method}($payload);
            } catch (InvalidCustomPayload $e) {
                return new Response('Webhook skipped due to invalid custom data.');
            }

            WebhookHandled::dispatch($payload);

            return new Response('Webhook was handled.');
        }

        return new Response('Webhook received but no handler found.');
    }

    /**
     * Handle a subscription created webhook.
     *
     * @throws InvalidCustomPayload
     */
    public function handleSubscriptionCreated(array $payload): void
    {
        $custom = $payload['meta']['custom_data'] ?? null;

        if (! isset($custom) || ! is_array($custom) || ! isset($custom['subscription_name'])) {
            throw new InvalidCustomPayload;
        }

        $billable = $this->findOrCreateCustomer($custom);

        $data = $payload['data'];
        $attributes = $payload['data']['attributes'];

        $subscription = $billable->subscriptions()->create([
            'name' => $custom['subscription_name'],
            'lemon_squeezy_id' => $data['id'],
            'status' => $attributes['status'],
            'product_id' => $attributes['product_id'],
            'variant_id' => $attributes['variant_id'],
            'trial_ends_at' => $attributes['trial_ends_at'] ? Carbon::make($attributes['trial_ends_at']) : null,
            'renews_at' => $attributes['renews_at'] ? Carbon::make($attributes['renews_at']) : null,
            'ends_at' => $attributes['ends_at'] ? Carbon::make($attributes['ends_at']) : null,
        ]);

        SubscriptionCreated::dispatch($billable, $subscription, $payload);
    }

    /**
     * Find or create a customer based on the custom values and return the billable model.
     *
     * @return \LaravelLemonSqueezy\Billable
     *
     * @throws InvalidCustomPayload
     */
    protected function findOrCreateCustomer(array $custom)
    {
        if (! isset($custom['billable_id'], $custom['billable_type'])) {
            throw new InvalidCustomPayload;
        }

        return LemonSqueezy::$customerModel::firstOrCreate([
            'billable_id' => $custom['billable_id'],
            'billable_type' => $custom['billable_type'],
        ])->billable;
    }
}
