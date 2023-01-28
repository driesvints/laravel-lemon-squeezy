<?php
use Tests\Fixtures\User;

it('cannnot overwrite the customer id or type for a billable', function () {
    config()->set('lemon-squeezy.store', 'lemon');

    $checkout = (new User)->checkout('variant_123')
        ->withCustomData([
            'billable_id' => '567',
            'billable_type' => 'App\\Models\\User',
        ]);

    expect($checkout->url())
        ->toBe('https://lemon.lemonsqueezy.com/checkout/buy/variant_123?checkout%5Bcustom%5D%5Bbillable_id%5D=user_123&checkout%5Bcustom%5D%5Bbillable_type%5D=users');
});

it('needs a configured store to generate checkouts', function () {
    $this->expectExceptionMessage('The Lemon Squeezy store was not configured.');

    (new User)->checkout('varian_123');
});
