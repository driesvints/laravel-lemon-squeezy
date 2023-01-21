<?php

namespace LaravelLemonSqueezy\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use LaravelLemonSqueezy\Events\WebhookHandled;
use LaravelLemonSqueezy\Events\WebhookReceived;
use LaravelLemonSqueezy\Exceptions\InvalidPassthroughPayload;
use LaravelLemonSqueezy\Http\Middleware\VerifyWebhookSignature;
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
            } catch (InvalidPassthroughPayload $e) {
                return new Response('Webhook skipped due to invalid passthrough data.');
            }

            WebhookHandled::dispatch($payload);

            return new Response('Webhook was handled.');
        }

        return new Response('Webhook received but no handler found.');
    }
}
