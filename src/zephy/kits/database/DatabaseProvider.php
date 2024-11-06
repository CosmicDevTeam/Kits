<?php

namespace zephy\kits\database;

use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use zephy\kits\Loader;
use zephy\kits\factory\extension\Kits;
use zephy\kits\utils\serializer\Serializer;
use zephy\kits\categorys\extension\Category;
use zephy\kits\factory\KitsFactory;
use zephy\kits\categorys\CategoryFactory;
class DatabaseProvider
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    protected DataConnector $database;

    public function init(array $data): void
    {
        $this->database = libasynql::create(Loader::getInstance(), $data, [
            'sqlite' => 'database/sqlite.sql',
            'mysql' => 'database/mysql.sql'
        ]);

        $this->database->executeGeneric('table.kits');
        $this->database->executeGeneric('table.categories');
    }

    public function createKit(Kits $kits): void
    {
        $items = array_map([Serializer::class, 'serialize'], $kits->getContents());
        $this->database->executeInsert('data.createKits', [
            'name' => $kits->getName(),
            'icon' => Serializer::serialize($kits->getIcon()),
            'permission' => $kits->getPermission(),
            'refresh' => $kits->getRefreshTime(),
            'items' => json_encode($items)
        ]);
    }

    public function createCategory(Category $category): void
    {
        $kits = array_map(fn($kit) => $kit->getName(), $category->getKits());
        $this->database->executeInsert('data.createCategory', [
            'name' => $category->getName(),
            'icon' => Serializer::serialize($category->getIcon()),
            'kits' => json_encode($kits)
        ]);
    }

    public function loadKits(): void
    {
        $this->database->executeSelect('data.selectKits', [], function (array $rows): void {
            foreach ($rows as $row) {
                KitsFactory::getInstance()->addKit(
                    $row['name'],
                    Serializer::deserialize($row['icon']),
                    $row['refresh'],
                    array_map([Serializer::class, 'deserialize'], json_decode($row['items'])),
                    $row['permission']
                );
            }
        });
    }

    public function loadCategories(): void
    {
        $this->database->executeSelect('data.selectCategory', [], function (array $rows): void {
            foreach ($rows as $row) {
                $kits = array_map(fn($kit) => KitsFactory::getInstance()->getKit($kit), json_decode($row['kits']));
                
                CategoryFactory::getInstance()->add($row['name'], Serializer::deserialize($row['icon']));
                $category = CategoryFactory::getInstance()->get($row['name']);
                $category?->setKits($kits);
            }
        });
    }

    public function updateCategory(Category $category): void
    {
        $this->database->executeChange('data.updateCategory', [
            'icon' => Serializer::serialize($category->getIcon()),
            'kits' => json_encode(array_map(fn($kit) => $kit->getName(), $category->getKits())),
            'name' => $category->getName()
        ]);
    }

    public function updateKit(Kits $kits): void
    {
        $items = array_map([Serializer::class, 'serialize'], $kits->getContents());
        $this->database->executeChange('data.updateKits', [
            'icon' => Serializer::serialize($kits->getIcon()),
            'permission' => $kits->getPermission(),
            'refresh' => $kits->getRefreshTime(),
            'items' => json_encode($items),
            'name' => $kits->getName()
        ]);
    }

    public function kill(): void
    {
        $this->database->waitAll();
        $this->database->close();
    }
}