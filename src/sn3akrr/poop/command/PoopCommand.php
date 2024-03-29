<?php namespace sn3akrr\poop\command;

use pocketmine\block\BlockTypeIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

use sn3akrr\poop\PoopCraft;

class PoopCommand extends Command implements PluginOwned{

	public function __construct(public PoopCraft $plugin, $name, $description){
		parent::__construct($name, $description);
		$this->setPermission("poopcraft.command");
		$this->setAliases(["poo"]);
	}

	public function execute(CommandSender $sender, string $label, array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.notplayer"));
			return;
		}
		if(
			!$sender->hasPermission(DefaultPermissions::ROOT_OPERATOR) && (
				!$sender->hasPermission("poopcraft.command") ||
				!$this->plugin->getConfig()->get("poop")
			)
		){
			$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.nopermission"));
			return;
		}

		if(empty($args)){
			$sender->sendMessage(
				TextFormat::RED . "Usage:" . PHP_EOL .
				"- /poop sneak" . PHP_EOL .
				"- /poop hand" . PHP_EOL .
				"- /poop inv"
			);
			return;
		}
		$pool = $this->plugin->getPoopPool();
		$option = strtolower(array_shift($args));
		switch($option){
			default:
				$sender->sendMessage(
					TextFormat::RED . "Usage:" . PHP_EOL .
					"- /poop sneak" . PHP_EOL .
					"- /poop hand" . PHP_EOL .
					"- /poop inv"
				);
				break;
			case "sneak":
				if(
					!$this->plugin->getConfig()->get("poop-sneak") &&
					!$sender->hasPermission("poopcraft.command.sneak")
				){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.sneak.nopermission"));
					return;
				}
				$ticker = $pool->getPoopSneaky($sender);
				if($ticker !== null){
					$pool->removePoopSneaky($sender);
				}else{
					$pool->addPoopSneaky($sender);
				}
				$sender->sendMessage(TextFormat::GREEN .
					($ticker !== null ?
						$this->plugin->getMessage("command.sneak.disabled") :
						$this->plugin->getMessage("command.sneak.enabled")
					)
				);
				break;
			case "hand":
				if(
					!$this->plugin->getConfig()->get("poop-hand") &&
					!$sender->hasPermission("poopcraft.command.hand")
				){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.hand.nopermission"));
					return;
				}
				$ticker = $this->plugin->getDefaultTicker();
				$item = clone $sender->getInventory()->getItemInHand();
				if($item->getTypeId() == BlockTypeIds::AIR){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.hand.noitem"));
					return;
				}
				if(!$ticker->poopStacks()){
					$item->setCount(1);
				}
				$this->plugin->poop($sender, $item);
				$sender->getInventory()->removeItem($item);
				$sender->sendMessage(TextFormat::GREEN . $this->plugin->getMessage("command.hand.success"));
				break;
			case "all":
			case "inv":
			case "inventory":
				if(
					!$this->plugin->getConfig()->get("poop-all") &&
					!$sender->hasPermission("poopcraft.command.all")
				){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.inventory.nopermission"));
					return;
				}
				if(empty($sender->getInventory()->getContents())){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.inventory.noitems"));
					return;
				}
				$ticker = $pool->getPoopInventory($sender);
				if($ticker !== null){
					$pool->removePoopInventory($sender);
				}else{
					$pool->addPoopInventory($sender);
				}
				$sender->sendMessage(TextFormat::GREEN .
					($ticker !== null ?
						$this->plugin->getMessage("command.inventory.disabled") :
						$this->plugin->getMessage("command.inventory.enabled")
					)
				);
				break;

			case "pack":
			case "jetpack":
				if(
					!$this->plugin->getConfig()->get("poop-jetpack") &&
					!$sender->hasPermission("poopcraft.command.jetpack")
				){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.jetpack.nopermission"));
					return;
				}
				if(empty($sender->getInventory()->getContents())){
					$sender->sendMessage(TextFormat::RED . $this->plugin->getMessage("command.jetpack.noitems"));
					return;
				}
				$ticker = $pool->getPoopPack($sender);
				if($ticker !== null){
					$pool->removePoopPack($sender);
				}else{
					$pool->addPoopPack($sender);
				}
				$sender->sendMessage(TextFormat::GREEN .
					($ticker !== null ?
						$this->plugin->getMessage("command.jetpack.disabled") :
						$this->plugin->getMessage("command.jetpack.enabled")
					)
				);
				break;
		}
	}

	public function getOwningPlugin() : Plugin{
		return $this->plugin;
	}

}