<?php

namespace LaravelLemonSqueezy;

use LaravelLemonSqueezy\Concerns\ManagesCheckouts;
use LaravelLemonSqueezy\Concerns\ManagesCustomer;

trait Billable
{
    use ManagesCustomer;
    use ManagesCheckouts;
}
