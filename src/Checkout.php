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

    public function url(): string
    {
        $store = config('lemon-squeezy.store');

        $toggles = collect(['logo', 'media', 'description', 'code'])
            ->filter(fn ($toggle) => ! $this->{$toggle})
            ->map(fn ($toggle) => $toggle.'=0')
            ->implode('&');

        return "https://{$store}.lemonsqueezy.com/checkout/buy/{$this->variant}".($toggles ? '?'.$toggles : '');
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
