<?php

namespace Tests\Unit;

use LaravelLemonSqueezy\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /** @test */
    public function it_asserts_true()
    {
        $customer = new Customer(['trial_ends_at' => now()->addDays(7)]);

        $this->assertTrue($customer->onGenericTrial());
    }
}
