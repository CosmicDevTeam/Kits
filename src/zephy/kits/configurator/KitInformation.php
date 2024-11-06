<?php

namespace zephy\kits\configurator;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use zephy\kits\factory\extension\Kits;

class KitInformation extends SimpleForm
{
    public function __construct(Kits $kit)
    {
        parent::__construct(function (Player $player, $data = null){
            if(is_null($data)) return;
        });

        $this->setTitle("Kit Information");
        $this->setContent("Select a kit to view information");

        $this->addButton("Set Category");
        $this->addButton("Set Items");
        $this->addButton("Set Refresh Time");

    }
}