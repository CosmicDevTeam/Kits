<?php

namespace zephy\kits\configurator;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use zephy\kits\categorys\CategoryFactory;

class CategoryCreator extends CustomForm
{
    public function __construct()
    {
        parent::__construct(function (Player $player, $data = null){
            if(is_null($data)) return;

            if(!is_string($data[0])){
                return;
            }

            if(!is_null(CategoryFactory::getInstance()->get($data[0]))){
                return;
            }
            $item = $player->getInventory()->getItemInHand();

            if($item->isNull()){
                return;
            }

            CategoryFactory::getInstance()->add($data[0], $item);
        });

        $this->setTitle("Category Creator");

        $this->addInput("Category Name", "Name of the category");
    }
}