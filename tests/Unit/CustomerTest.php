<?php

namespace Tests\Unit;

use LaravelLemonSqueezy\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_the_customer_is_on_a_generic_trial()
    {
        $customer = new Customer();
        $customer->setDateFormat('Y-m-d H:i:s');
        $customer->trial_ends_at = now()->addDays(7);

        $this->assertTrue($customer->onGenericTrial());
    }

    /** @test */
    public function it_can_determine_if_the_customer_has_an_expired_generic_trial()
    {
        $customer = new Customer();
        $customer->setDateFormat('Y-m-d H:i:s');
        $customer->trial_ends_at = now()->subDays(7);

        $this->assertTrue($customer->hasExpiredGenericTrial());
    }
}
