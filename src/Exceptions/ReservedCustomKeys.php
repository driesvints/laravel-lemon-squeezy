<?php

namespace LaravelLemonSqueezy\Exceptions;

use Exception;

class ReservedCustomKeys extends Exception
{
    public static function overwriteAttempt(): static
    {
        return new static(
            'You cannot use "billable_id" or "billable_type" as custom data keys because these are reserved keywords.'
        );
    }
}
