<?php

namespace LaravelLemonSqueezy;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class Checkout implements Responsable
{
    private string $variant;

    private bool $logo = true;

    private bool $media = true;

    private bool $description = true;

    private bool $code = true;

    private array $fields = [];

    private array $data = [];

    public function __construct(string $variant)
    {
        $this->variant = $variant;
    }

    public static function make(string $variant): static
    {
        return new static($variant);
    }

    public function withoutLogo(): static
    {
        $this->logo = false;

        return $this;
    }

    public function withoutMedia(): static
    {
        $this->media = false;

        return $this;
    }

    public function withoutDescription(): static
    {
        $this->description = false;

        return $this;
    }

    public function withoutCode(): static
    {
        $this->code = false;

        return $this;
    }

    public function withPrefilledFields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function withCustomData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function url(): string
    {
        $store = config('lemon-squeezy.store');

        $params = collect(['logo', 'media', 'description', 'code'])
            ->filter(fn ($toggle) => ! $this->{$toggle})
            ->mapWithKeys(fn ($toggle) => [$toggle => 0]);

        if ($this->fields) {
            $params = $params->merge($this->fields);
        }

        if ($this->data) {
            $params['custom'] = $this->data;
        }

        $params = $params->isNotEmpty() ? '?' . http_build_query($params->all()) : '';

        return "https://{$store}.lemonsqueezy.com/checkout/buy/{$this->variant}".$params;
    }

    public function redirect(): RedirectResponse
    {
        return Redirect::to($this->url(), 303);
    }

    public function toResponse($request): RedirectResponse
    {
        return $this->redirect();
    }
}
