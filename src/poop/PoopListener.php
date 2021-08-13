<?php namespace poop;

use pocketmine\event\Listener;

class PoopListener implements Listener{

	public $plugin;

	public function __construct(PoopCraft $plugin){
		$this->plugin = $plugin;
	}

}