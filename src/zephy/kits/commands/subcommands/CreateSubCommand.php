<?php

namespace zephy\kits\commands\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use zephy\kits\configurator\KitCreator;

class CreateSubCommand extends BaseSubCommand
{

    public function __construct()
    {
        parent::__construct("create", "Create a kit");
        $this->setPermission('kit.admin');
    }

    /**
     * @inheritDoc
     */
    protected function prepare(): void
    {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) return;

        $sender->sendForm(new KitCreator());
    }
}