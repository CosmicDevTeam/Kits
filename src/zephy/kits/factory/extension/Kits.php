<?php

namespace zephy\kits\factory\extension;

use pocketmine\item\Item;
use pocketmine\player\Player;
use zephy\kits\database\DatabaseProvider;
use zephy\kits\utils\Cooldowns;
use zephy\kits\utils\PermissionCreator;
class Kits
{
    private Cooldowns $cooldowns;

    /**
     * @param string $name
     * @param Item $icon
     * @param string $permission
     * @param int $refreshTime
     * @param Item[] $contents
     */
    public function __construct(
        private string $name,
        private Item $icon,
        private int $refreshTime,
        private array $contents = [],
        private string $permission = 'kit.use'
    )
    {
        $this->cooldowns = new Cooldowns();
        PermissionCreator::getInstance()->register($this->permission);
        DatabaseProvider::getInstance()->createKit($this);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Item
     */
    public function getIcon(): Item
    {
        return $this->icon;
    }


    /**
     * @return string
     */
    public function getPermission(): string
    {
        return $this->permission;
    }

    /**
     * @return Item[]
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param Item[] $contents
     */
    public function setContents(array $contents): void
    {
        $this->contents = $contents;
        DatabaseProvider::getInstance()->updateKit($this);
    }

    /**
     * @return Cooldowns
     */
    public function getCooldowns(): Cooldowns
    {
        return $this->cooldowns;
    }

    /**
     * @return int
     */
    public function getRefreshTime(): int
    {
        return $this->refreshTime;
    }

    /**
     * @param Player $player
     * @return void
     */
    public function give(Player $player): void
    {
        foreach ($this->contents as $content) {
            if($player->getInventory()->canAddItem($content)) {
                $player->getInventory()->addItem($content);
            }
        }

        $this->getCooldowns()->addCooldown($player->getName(), $this->getRefreshTime());
    }

}