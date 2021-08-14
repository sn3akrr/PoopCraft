<?php namespace sn3akrr\poop;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

use sn3akrr\poop\command\PoopCommand;

use sn3akrr\poop\utils\{
	PoopTicker,
	PoopPool
};

class PoopCraft extends PluginBase{

	const BUTTHOLE_HEIGHT = 0.35;

	/** @var string */
	public $lang;
	/** @var array */
	public $langData = [];

	public $poopSound;

	/** @var PoopTicker */
	public $defaultTicker;

	/** @var PoopPool */
	public $pool;

	public function onEnable(){
		$this->saveDefaultConfig();
		$this->lang = $this->getConfig()->get("language", "eng");
		$this->setupLanguageData();

		$pk = new PlaySoundPacket();
		$pk->soundName = $this->getConfig()->get("poop-sound", "none");
		$pk->volume = 100;
		$pk->pitch = 1;
		$this->poopSound = $pk;

		$this->defaultTicker = new PoopTicker(
			$this->getConfig()->get("force", 0.5),
			$this->getConfig()->get("rate", 5),
			$this->getConfig()->get("stacks")
		);
		$this->pool = new PoopPool($this);

		$this->getServer()->getCommandMap()->register("PoopCraft", new PoopCommand($this, "poop", "Poop!"));
		$this->getScheduler()->scheduleRepeatingTask(new PoopTask($this), 1);
	}

	public function getDefaultTicker() : PoopTicker{
		return $this->defaultTicker;
	}

	public function getPoopPool() : PoopPool{
		return $this->pool;
	}

	public function poop(Player $player, Item $item, ?PoopTicker $ticker = null) : void{
		$ticker = $ticker ?? $this->getDefaultTicker();

		$dv = $player->getDirectionVector()->multiply($ticker->getForce());
		$dv->x = -$dv->x; $dv->y = 0; $dv->z = -$dv->z;

		$sound = $this->poopSound;
		if($sound->soundName != "none"){
			$sound->x = $player->getX();
			$sound->y = $player->getY();
			$sound->z = $player->getZ();
			foreach(array_merge([$player], $player->getViewers()) as $viewer){
				$player->dataPacket($sound);
			}
		}
		$player->getLevel()->dropItem($player->asVector3()->add(0, self::BUTTHOLE_HEIGHT, 0), $item, $dv);
	}

	public function setupLanguageData() : int{
		$total = 0;
		foreach($this->getResources() as $resource){
			if($resource->isFile() and substr(($filename = $resource->getFilename()), 0, 5) === "lang_"){
				$this->langData[($name = substr($filename, 5, -5))] = $json = json_decode(file_get_contents($resource->getPathname()), true)["messages"] ?? [];
				$total++;
			}
		}
		return $total;
	}

	public function getDefaultLanguage() : string{
		return $this->lang;
	}

	public function getMessage(string $name, ?string $lang = null) : string{
		$lang = $lang ?? $this->getDefaultLanguage();
		return $this->langData[$lang][$name] ?? $name;
	}

}