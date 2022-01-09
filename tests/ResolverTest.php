<?php

namespace YanDourado\DI\Tests;

use YanDourado\DI\Definition;
use YanDourado\DI\Exception\ContainerException;
use YanDourado\DI\Resolver;
use YanDourado\DI\Tests\Classes\AbstractClass;

test('Expected exception if trying to resolve a abstract class', function () {

    $resolver = new Resolver();

    $resolver->resolve(new Definition(AbstractClass::class));
})->throws(ContainerException::class, sprintf('Class %s is not instantiable.', AbstractClass::class));

test('Expected exception if trying to resolve a invalid definition', function () {

    $resolver = new Resolver();

    $resolver->resolve(new Definition('INVALID'));

})->throws(ContainerException::class, 'Fail to resolve the concrete');
