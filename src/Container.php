<?php

declare(strict_types=1);

namespace Op4\DI;

use Op4\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
	/**
	 * @var array<string, Definition>
	 */
	protected array $definitions;

	protected Resolver $resolver;

	public function __construct()
	{
		$this->resolver = new Resolver($this);
	}

	public function get(string $id): mixed
	{
		if (!$this->has($id) && class_exists($id)) {
			$this->register($id);
		}

		if (!$this->has($id)) {
			throw new NotFoundException(sprintf('Reference to "%s" not found!', $id));
		}

		return $this->resolver->resolve($this->definitions[$id]);
	}

	public function has(string $id): bool
	{
		return isset($this->definitions[$id]);
	}

	public function register(string $id, object|string $concrete = null): Definition
	{
		$concrete = $concrete ?? $id;

		return $this->definitions[$id] = new Definition($concrete);
	}
}
