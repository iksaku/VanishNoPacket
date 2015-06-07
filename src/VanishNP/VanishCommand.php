<?php
namespace VanishNP;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use VanishNP\Loader;

class VanishCommand extends Command implements PluginIdentifiableCommand{
    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
        parent::__construct("vanish", "Hide from other players!", "/vanish [player]", ["v"]);
        $this->setPermission("vanish");
    }

    /**
     * @return Loader
     */
    public function getPlugin(){
       return $this->plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /vanish <player>");
                    return false;
                }
                $this->getPlugin()->switchVanish($sender);
                $sender->sendMessage(TextFormat::GRAY . "You're now " . ($this->getPlugin()->isVanished($sender) ? "vanished!" : "visible!"));
                break;
            case 1:
                if(!$sender->hasPermission("vanish.other")){
                    $sender->sendMessage($this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
                if($player === null){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $this->getPlugin()->switchVanish($player);
                $sender->sendMessage(TextFormat::GRAY .  $player->getDisplayName() . " is now " . ($this->getPlugin()->isVanished($player) ? "vanished!" : "visible!"));
                $player->sendMessage(TextFormat::GRAY . "You're now " . ($this->getPlugin()->isVanished($player) ? "vanished!" : "visible!"));
                break;
            default:
                $sender->sendMessage($sender instanceof Player ? $this->getUsage() : TextFormat::RED . "Usage: /vanish <player>");
                return false;
                break;
        }
        return true;
    }
}