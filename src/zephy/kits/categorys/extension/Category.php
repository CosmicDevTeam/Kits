<?php

namespace zephy\kits\categorys\extension;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\Item;
use pocketmine\player\Player;
use zephy\kits\database\DatabaseProvider;
use zephy\kits\factory\extension\Kits;
use zephy\kits\factory\KitsFactory;
use Exception;
class Category
{

    private string $name;
    private Item $icon;

    /** @var Kits[] $kits */
    private array $kits;

    public function __construct(
        string $name,
        Item $icon,
        array $kits = []
    )
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->kits = $kits;
        DatabaseProvider::getInstance()->createCategory($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): Item
    {
        return $this->icon;
    }

    public function getKits(): array
    {
        return $this->kits;
    }

    public function setKits(array $kits): void
    {
        $this->kits = $kits;
        DatabaseProvider::getInstance()->updateCategory($this);
    }

    public function addKit(Kits $kit): void
    {
        $this->kits[] = $kit;
        DatabaseProvider::getInstance()->updateCategory($this);
    }

    public function removeKit(Kits $kit): void
    {
        unset($this->kits[array_search($kit, $this->kits)]);
        DatabaseProvider::getInstance()->updateCategory($this);
    }

    public function open(Player $player): void
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);

        foreach ($this->kits as $kit) {

            $item = $kit->getIcon();
            $slot = $item->getNamedTag()->getInt("slot");

            if(is_null($item->getNamedTag()->getTag("slot")) or $slot > 26) {
                throw new Exception("Slot must be of type int , null given or length higher than 26 in kit: " . $kit->getName());
            }


            $menu->getInventory()->setItem($slot, $item);
        }

        $menu->setListener(function (InvMenuTransaction $transaction): InvMenuTransactionResult {
            $item = $transaction->getItemClicked();
            $player = $transaction->getPlayer();
            $name = $item->getNamedTag()->getString("kit");

            if(!is_null($item->getNamedTag()->getTag("kit"))) {

                $kit = KitsFactory::getInstance()->getKit($name);
                if(!is_null($kit)) {
                    if($player->hasPermission($kit->getPermission())) {
                        if(!$kit->getCooldowns()->hasCooldown($player->getName())) {
                            $kit->give($player);
                        }
                    }
                }
            }
            return $transaction->discard();
        });

    }

}