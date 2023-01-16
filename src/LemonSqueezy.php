<?php

namespace LaravelLemonSqueezy;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LaravelLemonSqueezy\Exceptions\LemonSqueezyException;

class LemonSqueezy
{
    /**
     * Indicates if migrations will be run.
     */
    public static bool $runsMigrations = true;

    /**
     * Perform a Lemon Squeezy API call.
     *
     * @throws \Exception
     * @throws \LaravelLemonSqueezy\Exceptions\LemonSqueezyException
     */
    protected static function api(string $method, string $uri, array $payload = []): Response
    {
        if (empty($apiKey = config('lemon-squeezy.api_key'))) {
            throw new Exception('Lemon Squeezy API key not set.');
        }

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withHeaders(['Authorization' => "Bearer {$apiKey}"])
            ->accept('application/vnd.api+json')
            ->contentType('application/vnd.api+json')
            ->$method("https://api.lemonsqueezy.com/v1/{$uri}", $payload);

        if ($response->failed()) {
            throw new LemonSqueezyException($response['errors'][0]['detail'], (int) $response['errors'][0]['status']);
        }

        return $response;
    }

    /**
     * Configure to not register any migrations.
     */
    public static function ignoreMigrations(): static
    {
        static::$runsMigrations = false;

        return new static;
    }
}
