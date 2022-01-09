<?php

namespace YanDourado\DI\Tests\Classes;

class Person
{
    public function __construct(
        public string  $name,
        public int     $age,
        public ?string $optional,
        public string  $default = 'default'
    )
    {
    }
}
