<?php

use Illuminate\Http\RedirectResponse;
use LaravelLemonSqueezy\Checkout;
use Tests\Fixtures\User;

beforeEach(fn () => config()->set('lemon-squeezy.store', 'lemon'));

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

it('can include prefilled fields', function () {
    $checkout = (new User)->checkout('variant_123')
        ->withPrefilledFields([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'billing_address' => [
                'country' => 'US',
                'state' => 'NY',
                'zip' => '10038',
            ],
            'tax_number' => 'GB123456789',
            'discount_code' => '10PERCENTOFF',
        ]);

    expect($checkout->url())
        ->toBe('https://lemon.lemonsqueezy.com/checkout/buy/variant_123?name=John+Doe&email=john%40example.com&billing_address%5Bcountry%5D=US&billing_address%5Bstate%5D=NY&billing_address%5Bzip%5D=10038&tax_number=GB123456789&discount_code=10PERCENTOFF');
});

it('can include custom data', function () {
    $checkout = (new User)->checkout('variant_123')
        ->withCustomData([
            'user_id' => '123',
        ]);

    expect($checkout->url())
        ->toBe('https://lemon.lemonsqueezy.com/checkout/buy/variant_123?custom%5Buser_id%5D=123');
});
