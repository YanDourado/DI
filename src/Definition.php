<?php

namespace YanDourado\DI;

class Definition
{
	private object|string $concrete;

	private mixed $resolved;

	private bool $shared;
	/**
	 * @var array<int|string, mixed>
	 */
	private array $arguments;

	public function __construct(object|string $concrete, bool $shared = false)
	{
		$this->concrete  = $concrete;
		$this->shared    = $shared;
		$this->arguments = [];
	}

	public function getConcrete(): object|string
	{
		return $this->concrete;
	}

	public function isResolved(): bool
	{
		return isset($this->resolved);
	}

	public function getResolved(): mixed
	{
		return $this->resolved;
	}

	public function setResolved(mixed $resolved): self
	{
		$this->resolved = $resolved;
		return $this;
	}

	public function isShared(): bool
	{
		return $this->shared;
	}

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
