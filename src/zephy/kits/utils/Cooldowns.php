<?php

namespace zephy\kits\utils;

use pocketmine\player\Player;

class Cooldowns
{
    private array $cooldowns = [];

    public function getCooldowns(): array
    {
        return $this->cooldowns;
    }

    public function hasCooldown(string $player)
    {
        if (isset($this->cooldowns[$player])) {
            return $this->cooldowns[$player] > time();
        }
        return false;
    }
    public function getCooldown(string $player)
    {
        return $this->cooldowns[$player] - time();
    }
    public function addCooldown(string $player, int $time)
    {
        $this->cooldowns[$player] = time() + $time;
    }

    public function removeCooldown(string $player)
    {
        unset($this->cooldowns[$player]);
    }
}