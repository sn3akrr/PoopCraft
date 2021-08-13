<?php namespace poop;

use pocketmine\scheduler\Task;

class PoopTask extends Task{

	public $plugin;
	
	public function __construct(PoopCraft $plugin){
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick){
		$this->plugin->getPoopPool()->tick();
	}

}