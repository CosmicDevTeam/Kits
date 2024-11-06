<?php

namespace zephy\kits;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use zephy\kits\database\DatabaseProvider;

class Loader extends PluginBase
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }
    protected function onEnable(): void
    {
        DatabaseProvider::getInstance()->init($this->getConfig()->get('database'));

        DatabaseProvider::getInstance()->loadKits();
        DatabaseProvider::getInstance()->loadCategories();
    }

    protected function onDisable(): void
    {
        DatabaseProvider::getInstance()->kill();
    }
}