<?php namespace sn3akrr\poop\utils;

class PoopTicker{

	/** @var int */
	public $ticks = 0;
	/** @var bool */
	public $sneaking = false;

	/** @var float */
	public $force = 0.4;

	/** @var float */
	public $rate = 10;

	/** @var bool */
	public $stacks = false;

	/** @var float */
	public $jetpackForce = 0.5;

	public function __construct(
		float $force = 0.4,
		float $rate = 10,
		bool $stacks = false,
		float $jetpackForce = 0.5
	){
		$this->force = $force;
		$this->rate = $rate;
		$this->stacks = $stacks;
		$this->jetpackForce = $jetpackForce;
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
	 * @return float
	 */
	public function getRate() : float{
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

	/**
	 * Returns the force at which poop jetpack will carry player
	 *
	 * @return float
	 */
	public function getJetpackForce() : float{
		return $this->jetpackForce;
	}

}