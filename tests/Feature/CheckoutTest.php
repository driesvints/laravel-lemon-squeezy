<?php

use Illuminate\Http\RedirectResponse;
use LaravelLemonSqueezy\Checkout;
use Tests\Fixtures\User;

beforeEach(fn() => config()->set('lemon-squeezy.store', 'lemon'));

it('can initiate a new checkout', function () {
    $checkout = (new User)->checkout('variant_123');

    expect($checkout)->toBeInstanceOf(Checkout::class);
    expect($checkout->url())
        ->toBe('https://lemon.lemonsqueezy.com/checkout/buy/variant_123');
});

it('can be redirected', function () {
    $checkout = (new User)->checkout('variant_123');

    expect($checkout->redirect())->toBeInstanceOf(RedirectResponse::class);
    expect($checkout->redirect()->getTargetUrl())
        ->toBe('https://lemon.lemonsqueezy.com/checkout/buy/variant_123');
});

it('can turn off toggles', function () {
    $checkout = (new User)->checkout('variant_123')
        ->withoutLogo()
        ->withoutMedia()
        ->withoutDescription()
        ->withoutCode();

    expect($checkout->url())
        ->toBe('https://lemon.lemonsqueezy.com/checkout/buy/variant_123?logo=0&media=0&description=0&code=0');
});
