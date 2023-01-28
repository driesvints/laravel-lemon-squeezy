<?php

namespace LaravelLemonSqueezy\Exceptions;

use Exception;

class MissingStoreException extends Exception
{
    public static function notConfigured(): static
    {
        return new static('The Lemon Squeezy store was not configured.');
    }
}
