<?php

namespace Op4\DI\Tests;

use Op4\DI\Definition;
use Op4\DI\Exception\ContainerException;
use Op4\DI\Resolver;
use Op4\DI\Tests\Classes\AbstractClass;

test('Expected exception if trying to resolve a abstract class', function () {

    $resolver = new Resolver();

    $resolver->resolve(new Definition(AbstractClass::class));
})->throws(ContainerException::class, sprintf('Class %s is not instantiable.', AbstractClass::class));

test('Expected exception if trying to resolve a invalid definition', function () {

    $resolver = new Resolver();

    $resolver->resolve(new Definition('INVALID'));

})->throws(ContainerException::class, 'Fail to resolve the concrete');
