<?php

namespace zephy\kits\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use zephy\kits\forms\CategoriesForm;
use zephy\kits\Loader;

class KitCommand extends BaseCommand
{

    public function __construct()
    {
        parent::__construct(Loader::getInstance(), "kit");
        $this->setPermission("kit.use");
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
    }

    /**
     * @inheritDoc
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) return;

        (new CategoriesForm())->send($sender);
    }
}