<?php

namespace LaravelLemonSqueezy;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class Checkout implements Responsable
{
    private string $store;

    private string $variant;

    private bool $logo = true;

    private bool $media = true;

    private bool $description = true;

    private bool $code = true;

    private array $fields = [];

    private array $custom = [];

    public function __construct(string $store, string $variant)
    {
        $this->store = $store;
        $this->variant = $variant;
    }

    public static function make(string $store, string $variant): static
    {
        return new static($store, $variant);
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

    public function withName(string $name): static
    {
        $this->fields['name'] = $name;

        return $this;
    }

    public function withEmail(string $email): static
    {
        $this->fields['email'] = $email;

        return $this;
    }

    public function withBillingAddress(string $country, string $state = null, string $zip = null): static
    {
        $this->fields['billing_address'] = array_filter([
            'country' => $country,
            'state' => $state,
            'zip' => $zip,
        ]);

        return $this;
    }

    public function withDiscountCode(string $discountCode): static
    {
        $this->fields['discount_code'] = $discountCode;

        return $this;
    }

    public function withTaxNumber(string $taxNumber): static
    {
        $this->fields['tax_number'] = $taxNumber;

        return $this;
    }

    public function withCustomData(array $custom): static
    {
        // These are reserved keys.
        if (isset($this->custom['billable_id'])) {
            unset($custom['billable_id']);
        }

        if (isset($this->custom['billable_type'])) {
            unset($custom['billable_type']);
        }

        $this->custom = $this->cleanQueryParameters(
            array_replace_recursive($this->custom, $custom)
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
        $params = collect(['logo', 'media', 'description', 'code'])
            ->filter(fn ($toggle) => ! $this->{$toggle})
            ->mapWithKeys(fn ($toggle) => [$toggle => 0]);

        if ($this->fields) {
            $params = $params->merge(array_filter($this->fields));
        }

        if ($this->custom) {
            $params['checkout'] = ['custom' => $this->custom];
        }

        $params = $params->isNotEmpty() ? '?'.http_build_query($params->all()) : '';

        return "https://{$this->store}.lemonsqueezy.com/checkout/buy/{$this->variant}".$params;
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
