<?php
namespace VanishNP\EventHandlers;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use VanishNP\Loader;

class DefaultHandler implements Listener{
    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $this->plugin->createSession($event->getPlayer());
        foreach($event->getPlayer()->getLevel()->getPlayers() as $p){
            $event->getPlayer()->hidePlayer($p);
        }
    }

    /**
     * @param PlayerQuitEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        $this->plugin->removeSession($event->getPlayer());
    }
}