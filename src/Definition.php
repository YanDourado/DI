<?php

namespace Op4\DI;

class Definition
{
	private object|string $concrete;

	private bool $shared;
	/**
	 * @var array<int|string, mixed>
	 */
	private array $arguments;

	public function __construct(object|string $concrete, bool $shared = true)
	{
		$this->concrete  = $concrete;
		$this->shared    = $shared;
		$this->arguments = [];
	}

	/**
	 * @return object|string
	 */
	public function getConcrete(): object|string
	{
		return $this->concrete;
	}

	/**
	 * @return bool
	 */
	public function isShared(): bool
	{
		return $this->shared;
	}

	/**
	 * @param bool $shared
	 * @return Definition
	 */
	public function setShared(bool $shared): self
	{
		$this->shared = $shared;
		return $this;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function getArguments(): array
	{
		return $this->arguments;
	}

	/**
	 * @param array<int|string, mixed> $arguments
	 * @return $this
	 */
	public function setArguments(array $arguments): self
	{
		$this->arguments = $arguments;
		return $this;
	}

	/**
	 * @param mixed $argument
	 * @return Definition
	 */
	public function addArgument(mixed $argument): self
	{
		$this->arguments[] = $argument;
		return $this;
	}
}
