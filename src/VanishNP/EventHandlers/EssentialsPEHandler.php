<?php
namespace VanishNP\EventHandlers;

use EssentialsPE\Events\PlayerVanishEvent;
use EssentialsPE\Events\SessionCreateEvent;
use pocketmine\event\Listener;

class EssentialsPEHandler implements Listener{
    /**
     * @param SessionCreateEvent $event
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onSessionCreate(SessionCreateEvent $event){
        $event->setValue("noPacket", true);
    }

    /**
     * @param PlayerVanishEvent $event
     *
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerVanish(PlayerVanishEvent $event){
        $event->setNoPacket(true);
    }
}