<?php

namespace LaravelLemonSqueezy;

use LaravelLemonSqueezy\Concerns\ManagesCustomer;
use LaravelLemonSqueezy\Concerns\ManagesPayments;

trait Billable
{
    use ManagesCustomer;
    use ManagesPayments;
}
