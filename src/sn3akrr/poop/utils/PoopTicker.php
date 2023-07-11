<?php namespace sn3akrr\poop\utils;

class PoopTicker{

	public int $ticks = 0;

	public bool $sneaking = false;

	public function __construct(
		public float $force = 0.4,
		public float $rate = 10,
		public bool $stacks = false,
		public float $jetpackForce = 0.5
	){
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