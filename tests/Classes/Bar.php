<?php

namespace YanDourado\DI\Tests\Classes;

class Bar
{
    public function __construct(
        public Foo $foo
    )
    {
    }

}
