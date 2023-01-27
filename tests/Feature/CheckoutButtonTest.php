<?php

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Tests\Fixtures\User;

uses(InteractsWithViews::class);

it('can render a button', function () {
    $view = $this->blade(
        '<x-lemon-button :href="$href">Buy Now</x-lemon-button>',
        ['href' => 'https://lemon.lemonsqueezy.com/checkout/buy/variant_123']
    );

    $expect = <<<'HTML'
        <a
            href="https://lemon.lemonsqueezy.com/checkout/buy/variant_123?embed=1"
            class="lemonsqueezy-button"
        >
            Buy Now
        </a>
        HTML;

    $view->assertSee($expect, false);
});

it('can render an overlay with a dark background', function () {
    $view = $this->blade(
        '<x-lemon-button :href="$href" dark>Buy Now</x-lemon-button>',
        ['href' => 'https://lemon.lemonsqueezy.com/checkout/buy/variant_123']
    );

    $expect = <<<'HTML'
        <a
            href="https://lemon.lemonsqueezy.com/checkout/buy/variant_123?embed=1&dark=1"
            class="lemonsqueezy-button"
        >
            Buy Now
        </a>
        HTML;

    $view->assertSee($expect, false);
});

it('can render a button with disabled toggles', function () {
    $view = $this->blade(
        '<x-lemon-button :href="$href">Buy Now</x-lemon-button>',
        ['href' => 'https://lemon.lemonsqueezy.com/checkout/buy/variant_123?logo=0&media=0']
    );

    $expect = <<<'HTML'
        <a
            href="https://lemon.lemonsqueezy.com/checkout/buy/variant_123?logo=0&media=0&embed=1"
            class="lemonsqueezy-button"
        >
            Buy Now
        </a>
        HTML;

    $view->assertSee($expect, false);
});

it('can render a checkout instance', function () {
    config()->set('lemon-squeezy.store', 'lemon');

    $view = $this->blade(
        '<x-lemon-button :href="$href">Buy Now</x-lemon-button>',
        ['href' => (new User)->checkout('variant_123')->withoutLogo()]
    );

    $expect = <<<'HTML'
        <a
            href="https://lemon.lemonsqueezy.com/checkout/buy/variant_123?logo=0&embed=1"
            class="lemonsqueezy-button"
        >
            Buy Now
        </a>
        HTML;

    $view->assertSee($expect, false);
});
