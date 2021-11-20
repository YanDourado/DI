<?php

namespace Op4\DI\Tests\Classes;

class Bar
{
    public function __construct(
        public Foo $foo
    )
    {
    }

}