<?php namespace sn3akrr\poop\utils;

use pocketmine\{
	Server,
	Player
};
use pocketmine\utils\TextFormat;

use poop\PoopCraft;

class PoopPool{

	public $plugin;

	/** @var array */
	public $poopSneak = [];
	/** @var array */
	public $poopAll = [];

	public function __construct(PoopCraft $plugin){
		$this->plugin = $plugin;
	}

	public function tick() : void{
		foreach($this->poopSneak as $name => $ticker){
			$player = Server::getInstance()->getPlayerExact($name);
			if(!$player instanceof Player){
				unset($this->poopSneak[$name]);
				continue;
			}

			if($player->isSneaking()){
				if($ticker->tick()){
					$item = clone $player->getInventory()->getItemInHand();
					if(!$ticker->poopStacks()){
						$item->setCount(1);
					}
					$this->plugin->poop($player, $item, $ticker);
					$player->getInventory()->removeItem($item);
				}
			}
		}

		foreach($this->poopAll as $name => $ticker){
			$player = Server::getInstance()->getPlayerExact($name);
			if(!$player instanceof Player){
				unset($this->poopAll[$name]);
				continue;
			}

			if($ticker->tick()){
				if(empty($player->getInventory()->getContents())){
					unset($this->poopAll[$name]);
					$player->sendMessage(TextFormat::GREEN . $this->plugin->getMessage("command.inventory.done"));
					continue;
				}
				foreach($player->getInventory()->getContents() as $item){
					$item = clone $item;
					if(!$ticker->poopStacks()){
						$item->setCount(1);
					}
					$this->plugin->poop($player, $item, $ticker);
					$player->getInventory()->removeItem($item);
					break;
				}
			}
		}
	}

	public function getPoopSneakys() : array{
		return $this->poopSneak;
	}

	public function addPoopSneaky(Player $player, ?PoopTicker $settings = null) : void{
		$this->poopSneak[$player->getName()] = ($settings ?? $this->plugin->getDefaultTicker());
	}

	public function removePoopSneaky(Player $player) : void{
		unset($this->poopSneak[$player->getName()]);
	}

	public function getPoopSneaky(Player $player) : ?PoopTicker{
		return $this->poopSneak[$player->getName()] ?? null;
	}

	public function getPoopInventories() : array{
		return $this->poopAll;
	}

	public function getPoopInventory(Player $player) : ?PoopTicker{
		return $this->poopAll[$player->getName()] ?? null;
	}

	public function addPoopInventory(Player $player, ?PoopTicker $settings = null) : void{
		$this->poopAll[$player->getName()] = ($settings ?? $this->plugin->getDefaultTicker());
	}

	public function removePoopInventory(Player $player) : void{
		unset($this->poopAll[$player->getName()]);
	}

}