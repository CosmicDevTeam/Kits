<?php

namespace zephy\kits\utils;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\SingletonTrait;

class PermissionCreator
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    public function register(string $permission): void
    {
        if(PermissionManager::getInstance()->getPermission($permission) === null){
            PermissionManager::getInstance()->addPermission(new Permission($permission));
            $perm = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
            $perm?->addChild($permission, true);
        }
    }
    
    public function remove(string $permission): void
    {
        PermissionManager::getInstance()->removePermission($permission);
        $perm = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
        $perm?->removeChild($permission, true);
    }
}