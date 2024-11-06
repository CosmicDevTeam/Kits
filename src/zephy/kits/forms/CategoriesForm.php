<?php

namespace zephy\kits\forms;

use muqsit\invmenu\InvMenu;

use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\player\Player;
use zephy\kits\categorys\CategoryFactory;
use Exception;

class CategoriesForm extends InvMenu
{
    public function __construct()
    {
      parent::__construct(self::create(InvMenuTypeIds::TYPE_CHEST)->getType());

      foreach (CategoryFactory::getInstance()->getAll() as $category) {
          $slot = $category->getIcon()->getNamedTag()->getInt("slot");

          if(is_null($slot)) {
              throw new Exception("Slot must be of type string , null given in category: " . $category->getName());
          }
          $this->getInventory()->setItem($slot, $category->getIcon());
      }

      $this->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
          $item = $transaction->getItemClicked();
          $player = $transaction->getPlayer();

          if(!is_null($item->getNamedTag()->getString("category"))){
              $category = CategoryFactory::getInstance()->get($item->getNamedTag()->getString("category"));
              $category?->open($player);
              $player->removeCurrentWindow();

          }
          return $transaction->discard();
      });
    }

}