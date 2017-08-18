<?php
namespace VanishNP;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class VanishCommand extends PluginCommand{
    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin) {
        parent::__construct("vanish", $plugin);
        $this->setDescription("Hide from other players!");
        $this->setUsage("/vanish [player]");
        $this->setAliases(["v"]);
        $this->setPermission("vanish");
    }

    /**
     * @return Loader
     */
    public function getPlugin() : Plugin {
       return $this->plugin;
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if(!$this->testPermission($sender)) {
            return false;
        }
        switch(count($args)) {
            case 0:
                if(!$sender instanceof Player) {
                    return false;
                }
                $this->getPlugin()->switchVanish($sender);
                $sender->sendMessage(TextFormat::GRAY . "You're now " . ($this->getPlugin()->isVanished($sender) ? "vanished!" : "visible!"));
            break;
            case 1:
                if(!$sender->hasPermission("vanish.other")) {
                    $sender->sendMessage($this->getPermissionMessage());
                    return true;
                }
                $player = $this->getPlugin()->getServer()->getPlayer($args[0]);
                if($player === null) {
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return true;
                }
                $this->getPlugin()->switchVanish($player);
                $sender->sendMessage(TextFormat::GRAY .  $player->getDisplayName() . " is now " . ($this->getPlugin()->isVanished($player) ? "vanished!" : "visible!"));
                $player->sendMessage(TextFormat::GRAY . "You're now " . ($this->getPlugin()->isVanished($player) ? "vanished!" : "visible!"));
            break;
        }
        return true;
    }
}