<?php
namespace VanishNP;

use EssentialsPE\Loader as EssentialsPE;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use VanishNP\EventHandlers\DefaultHandler;
use VanishNP\EventHandlers\EssentialsPEHandler;

class Loader extends PluginBase{
    /** @var null|EssentialsPE */
    private $esspe;

    public function onEnable(){
        $this->esspe = $this->getServer()->getPluginManager()->getPlugin("EssentialsPE");
        if(!$this->getEssentialsPEAPI()){
            $this->getServer()->getPluginManager()->registerEvents(new DefaultHandler($this), $this);
            $this->getServer()->getCommandMap()->register("VanishNP", new VanishCommand($this));
        }else{
            $this->getServer()->getPluginManager()->registerEvents(new EssentialsPEHandler(), $this);
            $this->getServer()->getLogger()->info(TextFormat::YELLOW . "Enabled " . TextFormat::GREEN . "EssentialsPE" . TextFormat::YELLOW . " plugin support for " . TextFormat::RED . "Vanish (No Packet)");
        }
    }

    /**
     * @return bool|EssentialsPE
     */
    private function getEssentialsPEAPI(){
        if($this->esspe === null){
            return false;
        }
        return $this->esspe;
    }

    /*
     *  .----------------.  .----------------.  .----------------.
     * | .--------------. || .--------------. || .--------------. |
     * | |      __      | || |   ______     | || |     _____    | |
     * | |     /  \     | || |  |_   __ \   | || |    |_   _|   | |
     * | |    / /\ \    | || |    | |__) |  | || |      | |     | |
     * | |   / ____ \   | || |    |  ___/   | || |      | |     | |
     * | | _/ /    \ \_ | || |   _| |_      | || |     _| |_    | |
     * | ||____|  |____|| || |  |_____|     | || |    |_____|   | |
     * | |              | || |              | || |              | |
     * | '--------------' || '--------------' || '--------------' |
     *  '----------------'  '----------------'  '----------------'
     *
     */

    /** @var array */
    private $sessions = [];

    /**
     * @param Player $player
     */
    public function createSession(Player $player){
        $spl = spl_object_hash($player);
        if(!isset($this->sessions[$spl])){
            $this->sessions[$spl] = false;
        }
    }

    /**
     * @param Player $player
     */
    public function removeSession(Player $player){
        $spl = spl_object_hash($player);
        if(isset($this->sessions[$spl])){
            unset($this->sessions[$spl]);
        }
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isVanished(Player $player){
        if(!$this->getEssentialsPEAPI()){
            $spl = spl_object_hash($player);
            if(!isset($this->sessions[$spl])){
                $this->sessions[$spl] = false;
            }
            return $this->sessions[$spl];
        }else{
            return $this->getEssentialsPEAPI()->isVanished($player);
        }
    }

    /**
     * @param Player $player
     * @param bool $state
     */
    public function setVanish(Player $player, $state){
        if(!is_bool($state)){
            return;
        }
        if(!$this->getEssentialsPEAPI()){
            $player->setDataFlag(Player::DATA_FLAGS, Player::DATA_FLAG_INVISIBLE, $state);
            $player->setDataProperty(Player::DATA_SHOW_NAMETAG, Player::DATA_TYPE_BYTE, ($state ? 0 : 1));
            foreach($player->getLevel()->getPlayers() as $p){
                if(!$state){
                    $p->showPlayer($player);
                }else{
                    $p->hidePlayer($player);
                }
            }
            $this->sessions[spl_object_hash($player)] = $state;
        }else{
            $this->getEssentialsPEAPI()->setVanish($player, $state, true);
        }
    }

    /**
     * @param Player $player
     */
    public function switchVanish(Player $player){
        $this->setVanish($player, !$this->isVanished($player));
    }

    /**
     * @param Player $player
     * @param Level $origin
     * @param Level $target
     */
    public function switchLevelVanish(Player $player, Level $origin, Level $target){
        if(!$this->getEssentialsPEAPI()){
            foreach($origin->getPlayers() as $p){
                if($p !== $player){
                    if($this->isVanished($player)){
                        $p->showPlayer($p);
                    }
                    if($this->isVanished($p)){
                        $player->showPlayer($player);
                    }
                }
            }
            foreach($target->getPlayers() as $p){
                if($p !== $player){
                    if($this->isVanished($player)){
                        $p->hidePlayer($player);
                    }
                    if($this->isVanished($p)){
                        $player->hidePlayer($p);
                    }
                }
            }
        }else{
            $this->getEssentialsPEAPI()->switchLevelVanish($player, $origin, $target);
        }
    }
}