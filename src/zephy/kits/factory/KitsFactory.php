<?php

namespace zephy\kits\factory;

use pocketmine\item\Item;
use pocketmine\utils\SingletonTrait;
use zephy\kits\factory\extension\Kits;

class KitsFactory
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }
    /** @var Kits[] $kits */
    private array $kits = [];

    public function getKits(): array
    {
        return $this->kits;
    }

    public function getKit(string $name): ?Kits
    {
        return $this->kits[$name] ?? null;
    }

    public function addKit(string $name, Item $kitIcon, int $refresh, array $items, string $permission = 'kit.use'): void
    {
        $this->kits[$name] = new Kits($name, $kitIcon, $refresh, $items, $permission);
    }

}