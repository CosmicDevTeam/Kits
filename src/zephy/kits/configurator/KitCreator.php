<?php

namespace zephy\kits\configurator;

use jojoe77777\FormAPI\CustomForm;
use muqsit\invmenu\type\InvMenuTypeIds;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use zephy\kits\factory\KitsFactory;

class KitCreator extends CustomForm
{
    public function __construct()
    {
        parent::__construct(function (Player $player, $data = null){
            if(is_null($data)) return;

            if(!is_string($data[0]) and !is_numeric($data[1])){
                return;
            }

            if(!is_null(KitsFactory::getInstance()->getKit($data[0]))){
                return;
            }

            $item = $player->getInventory()->getItemInHand();

            if($item->isNull()){
                return;
            }

            KitsFactory::getInstance()->addKit($data[0], $item, round($data[1]), [], $data[2]);
            $this->editContents($player, $data[0]);

        });

        $this->setTitle("Kit Creator");

        $this->addInput("Kit Name", "Name of the kit");
        $this->addInput("Refresh Time", "Time in seconds", 60);
        $this->addInput("Permission", "Leave empty if not permission required", "kit.use");

    }

    public function editContents(Player $player, string $name): void
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);

        $kit = KitsFactory::getInstance()->getKit($name);
        $menu->getInventory()->setContents($kit?->getContents());

        $menu->setInventoryCloseListener(function (Player $player, Inventory $inventory) use ($kit){
            $kit->setContents($inventory->getContents());

            unset($this->data);
        });
    }
}