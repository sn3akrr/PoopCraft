<?php namespace sn3akrr\poop;

use pocketmine\scheduler\Task;

class PoopTask extends Task{


	public function __construct(public PoopCraft $plugin){
	}

	public function onRun() : void{
		$this->plugin->getPoopPool()->tick();
	}

}