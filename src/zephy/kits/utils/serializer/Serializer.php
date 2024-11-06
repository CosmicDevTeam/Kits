<?php

namespace zephy\kits\utils\serializer;

use pocketmine\item\Item;
use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\TreeRoot;
use InvalidArgumentException;

class Serializer
{
    public static function serialize(Item $item): string
    {
        $nbt = new LittleEndianNbtSerializer();

        return base64_encode($nbt->write(new TreeRoot($item->nbtSerialize())));
    }

    public static function deserialize(string $data): Item
    {
        $nbt = new LittleEndianNbtSerializer();

        try {
            $item = Item::nbtDeserialize($nbt->read(base64_decode($data))->mustGetCompoundTag());
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid serialized item data");
        }
        return $item;
    }
}