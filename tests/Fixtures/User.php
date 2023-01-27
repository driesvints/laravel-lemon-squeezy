<?php

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use LaravelLemonSqueezy\Billable;

class User extends Model
{
    use Billable;
}
