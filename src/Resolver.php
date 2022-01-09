<?php

namespace YanDourado\DI;

use YanDourado\DI\Exception\ContainerException;
use YanDourado\DI\Exception\NotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

class Resolver
{
	private ContainerInterface $container;

	public function __construct(
		ContainerInterface $container = null
	) {
		$this->container = $container ?? new Container();
	}

	/**
	 * @throws ContainerException|ReflectionException
	 */
	public function resolve(Definition $definition): mixed
	{
		$concrete = $definition->getConcrete();
		$resolved = match (true) {
			($definition->isResolved() && $definition->isShared()) => $definition->getResolved(),
			is_callable($concrete)                                 => $this->resolveClosure($definition),
			(is_string($concrete) && class_exists($concrete))      => $this->resolveClass($definition),
			default                                                => throw new ContainerException('Fail to resolve the concrete')
		};

		if ($definition->isShared()) {
			$definition->setResolved($resolved);
		}

		return $resolved;
	}

	/**
	 * @throws ReflectionException
	 */
	private function resolveClosure(Definition $definition): mixed
	{
		$callable           = $definition->getConcrete();
		$reflectionFunction = new ReflectionFunction($callable);
		return $callable(...$this->resolveParameters(
			$reflectionFunction->getParameters(),
			$definition->getArguments()
		));
	}

	/**
	 * @param ReflectionParameter[] $reflectionParameters
	 * @param array<int, mixed> $arguments
	 * @return array<int, mixed>
	 */
	private function resolveParameters(array $reflectionParameters, array $arguments): array
	{
		return array_reduce($reflectionParameters, function (array $parameters, ReflectionParameter $reflectionParameter) use ($arguments) {
			$parameters[] = $this->resolveParameter($reflectionParameter, $arguments);
			return $parameters;
		}, []);
	}

	/**
	 * @param ReflectionParameter $reflection
	 * @param array<int|string, mixed> $arguments
	 * @return mixed
	 * @throws ReflectionException|ContainerExceptionInterface|NotFoundExceptionInterface
	 */
	private function resolveParameter(ReflectionParameter $reflection, array $arguments): mixed
	{
		/** @var ReflectionNamedType|null $reflectionType */
		$reflectionType = $reflection->getType();
		$className      = $reflectionType && !$reflectionType->isBuiltin()
			? new ReflectionClass($reflectionType->getName())
			: null;

		return match (true) {
			isset($arguments[$reflection->getName()])                => $arguments[$reflection->getName()],
			isset($arguments[$reflection->getPosition()])            => $arguments[$reflection->getPosition()],
			isset($className)                                        => $this->container->get($className->name),
			$reflection->isDefaultValueAvailable()                   => $reflection->getDefaultValue(),
			($reflection->isOptional() || $reflection->allowsNull()) => null,
			default                                                  => throw new NotFoundException(sprintf(
				'Unresolvable dependency resolving "%s" in class "%s"',
				$reflection->name,
				$reflection->getDeclaringClass()?->getName()
			))
		};
	}

	/**
	 * @throws ContainerException|ReflectionException
	 */
	private function resolveClass(Definition $definition): object
	{
		$concrete   = $definition->getConcrete();
		$reflection = new ReflectionClass($concrete);

		if (!$reflection->isInstantiable()) {
			throw new ContainerException(sprintf('Class %s is not instantiable.', $concrete));
		}

		$constructor = $reflection->getConstructor();

		if ($constructor === null) {
			return $reflection->newInstanceWithoutConstructor();
		}

		return $reflection->newInstanceArgs(
			$this->resolveParameters($constructor->getParameters(), $definition->getArguments())
		);
	}
}
