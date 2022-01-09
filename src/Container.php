<?php

declare(strict_types=1);

namespace YanDourado\DI;

use YanDourado\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
	/**
	 * @var array<string, Definition>
	 */
	protected array $definitions;

	/**
	 * @var array<string, mixed>
	 */
	protected array $instances;

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

		if (isset($this->instances[$id])) {
			return $this->instances[$id];
		}

		$resolved = $this->resolver->resolve($this->definitions[$id]);

		if ($this->isShared($id)) {
			$this->instances[$id] = $resolved;
		}

		return $resolved;
	}

	public function has(string $id): bool
	{
		return isset($this->definitions[$id]) || isset($this->instances[$id]);
	}

	public function register(string $id, object|string $concrete = null): Definition
	{
		$concrete = $concrete ?? $id;

		return $this->definitions[$id] = new Definition($concrete);
	}

	private function isShared(string $id): bool
	{
		return isset($this->instances[$id]) ||
			(isset($this->definitions[$id]) && $this->definitions[$id]->isShared());
	}

	public function singleton(string $id, object|string $concrete = null): Definition
	{
		$concrete = $concrete ?? $id;

		return $this->definitions[$id] = new Definition($concrete, true);
	}
}
