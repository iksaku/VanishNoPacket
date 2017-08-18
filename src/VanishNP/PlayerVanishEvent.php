<?php
namespace VanishNP;

use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;

class PlayerVanishEvent extends PluginEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player $player */
    protected $player;

    /** @var bool $isVanished */
    protected $isVanished;

    /** @var bool $willVanish */
    protected $willVanish;

    /** @var string[] $keepHiddenFor */
    protected $keepHiddenFor = [];

    /**
     * @param Loader $plugin
     * @param Player $player
     * @param bool $willVanish
     */
    public function __construct(Loader $plugin, Player $player, bool $willVanish) {
        parent::__construct($plugin);
        $this->player = $player;
        $this->isVanished = $plugin->isVanished($player);
        $this->willVanish = $willVanish;
    }
    /**
     * @return Player
     */
    public function getPlayer() {
        return $this->player;
    }

    /**
     * @return bool
     */
    public function isVanished() {
        return $this->isVanished;
    }

    /**
     * @return bool
     */
    public function willVanish() {
        return $this->willVanish;
    }

    /**
     * @param bool $value
     */
    public function setVanished(bool $value) {
	    $this->willVanish = $value;
    }

    /**
     * @param Player $player
     */
    public function keepHiddenFor(Player $player) {
        $this->keepHiddenFor[] = $player->getName();
    }

    /**
     * @return string[]
     */
    public function getHiddenFor() {
        return $this->keepHiddenFor;
    }
}