<?php

namespace YanDourado\DI\Tests;

use YanDourado\DI\Container;
use YanDourado\DI\Tests\Classes\Bar;
use YanDourado\DI\Tests\Classes\Foo;
use YanDourado\DI\Tests\Classes\Person;

test('Register a reference in Container', function () {
    $container = new Container();

    $container->register('foo', Foo::class);

    $foo = $container->get('foo');
    expect($foo)->toBeInstanceOf(Foo::class);
});

test('Register a not shared reference in Container', function () {
    $container = new Container();

    $container->register(Foo::class);

    $bar = $container->get(Bar::class);

    expect($container->get(Foo::class) === $container->get(Foo::class))->toBeFalse();
    expect($bar->foo === $container->get(Foo::class))->toBeFalse();
});

test('Register a shared reference in Container', function () {
    $container = new Container();

    $container->singleton(Foo::class);

    $bar = $container->get(Bar::class);

    expect($container->get(Foo::class) === $container->get(Foo::class))->toBeTrue();
    expect($bar->foo === $container->get(Foo::class))->toBeTrue();

});

test('Register a reference with parameters in Container', function () {
    $container = new Container();

    $container->register('bar', Bar::class);

    $container->register('person', Person::class)
        ->addArgument('John Doe')
        ->addArgument(100);

    $bar = $container->get('bar');
    $person = $container->get('person');

    expect($bar)->toBeInstanceOf(Bar::class);
    expect($bar->foo)->toBeInstanceOf(Foo::class);

    expect($person)->toBeInstanceOf(Person::class)
        ->toMatchObject([
            'name'     => 'John Doe',
            'age'      => 100,
            'optional' => null,
            'default'  => 'default'
        ]);
});

test('Register a factory in Container', function () {
    $container = new Container();

    $container->register('foo', fn() => new Foo());

    $foo = $container->get('foo');
    expect($foo)->toBeInstanceOf(Foo::class);
});

test('Register a factory with parameters in Container', function () {
    $container = new Container();

    $container->register('bar', fn(Foo $foo) => new Bar($foo));

    $bar = $container->get('bar');

    expect($bar)->toBeInstanceOf(Bar::class);
    expect($bar->foo)->toBeInstanceOf(Foo::class);
});
