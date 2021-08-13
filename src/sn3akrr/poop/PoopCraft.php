<?php namespace sn3akrr\poop;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\item\Item;

use poop\command\PoopCommand;

use poop\utils\{
	PoopTicker,
	PoopPool
};

class PoopCraft extends PluginBase{

	const BUTTHOLE_HEIGHT = 0.35;

	/** @var string */
	public $lang;
	/** @var array */
	public $langData = [];

	/** @var PoopTicker */
	public $defaultTicker;

	/** @var PoopPool */
	public $pool;

	public function onEnable(){
		$this->getServer()->getCommandMap()->register("PoopCraft", new PoopCommand($this, "poop", "Poop!"));

		$this->saveDefaultConfig();
		$this->lang = $this->getConfig()->get("language");
		$this->setupLanguageData();

		$this->defaultTicker = new PoopTicker(
			$this->getConfig()->get("force"),
			$this->getConfig()->get("rate"),
			$this->getConfig()->get("stacks")
		);
		$this->pool = new PoopPool($this);

		$this->getScheduler()->scheduleRepeatingTask(new PoopTask($this), 1);

		$this->getLogger()->notice($this->getMessage("plugin.enabled"));
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