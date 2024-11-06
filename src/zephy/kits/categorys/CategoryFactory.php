<?php

namespace zephy\kits\categorys;

use pocketmine\item\Item;
use pocketmine\utils\SingletonTrait;
use zephy\kits\categorys\extension\Category;

class CategoryFactory
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }


     /** @var Category[] $categorys */
     private array $categories = [];

    public function getAll(): array
    {
        return $this->categories;
    }

    public function get(string $name): ?Category
    {
        return $this->categories[$name] ?? null;
    }

    public function add(string $name, Item $item): void
    {
        $this->categories[$name] = new Category($name, $item);
    }

}