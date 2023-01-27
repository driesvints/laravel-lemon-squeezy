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
        $this->fields = $this->cleanQueryParameters(
            array_replace_recursive($this->fields, $fields)
        );

        return $this;
    }

    public function withCustomData(array $data): static
    {
        // These are reserved keys.
        if (isset($this->data['billable_id'])) {
            unset($data['billable_id']);
        }

        if (isset($this->data['billable_type'])) {
            unset($data['billable_type']);
        }

        $this->data = $this->cleanQueryParameters(
            array_replace_recursive($this->data, $data)
        );

        return $this;
    }

    private function cleanQueryParameters(array $params): array
    {
        return collect($params)
            ->map(function ($value) {
                if (is_array($value)) {
                    return collect($value)
                        ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                        ->all();
                }

                return is_string($value) ? trim($value) : $value;
            })->filter(function ($value) {
                if (is_array($value)) {
                    return collect($value)->filter()->all();
                }

                return ! empty($value);
            })->all();
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

        $params = $params->isNotEmpty() ? '?'.http_build_query($params->all()) : '';

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
