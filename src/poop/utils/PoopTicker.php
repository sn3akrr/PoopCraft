<?php namespace poop\utils;

class PoopTicker{

	public $ticks = 0;

	public $sneaking = false;

	/** @var float */
	public $force = 0.4;

	/** @var int */
	public $rate = 10;

	/** @var bool */
	public $stacks = false;

	public function __construct(float $force = 0.4, float $rate = 10, bool $stacks = false){
		$this->force = $force;
		$this->rate = $rate;
		$this->stacks = $stacks;
	}

	public function tick() : bool{
		$this->ticks++;
		return $this->ticks % $this->getRate() == 0;
	}

	/**
	 * The force at which the poop exits le bumhole
	 *
	 * @return float
	 */
	public function getForce() : float{
		return $this->force;
	}

	/**
	 * Returns the default max tick rate of pooping (For sneak mode too)
	 *
	 * @return int
	 */
	public function getRate() : int{
		return $this->rate;
	}

	/**
	 * Whether you can poop out full item stacks or one item at a time
	 *
	 * @return bool
	 */
	public function poopStacks() : bool{
		return $this->stacks;
	}

}