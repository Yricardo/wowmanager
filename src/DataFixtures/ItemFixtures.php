<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = $this->generateItems();
        
        // Loop through the items and create entities
        foreach ($items as $index => $itemData) {
            $item = new Item();
            $item->setName($itemData['name']);
            $item->setIdWow($itemData['idWow']);
            $item->setImgPath($itemData['imgPath']);

            // Persist the item
            $manager->persist($item);

            // Add a reference for use in other fixtures
            $this->addReference('item_' . $index, $item);
        }

        // Flush all changes to the database
        $manager->flush();
    }

    private function generateItems(): array
    {
        // Define a list of items with their properties
        return [
            ['name' => 'Sword of Valor', 'idWow' => 1001, 'imgPath' => 'images/items/sword_of_valor.jpg'],
            ['name' => 'Shield of Eternity', 'idWow' => 1002, 'imgPath' => 'images/items/shield_of_eternity.jpg'],
            ['name' => 'Potion of Healing', 'idWow' => 1003, 'imgPath' => 'images/items/potion_of_healing.jpg'],
            ['name' => 'Ring of Power', 'idWow' => 1004, 'imgPath' => 'images/items/ring_of_power.jpg'],
            ['name' => 'Staff of Wisdom', 'idWow' => 1005, 'imgPath' => 'images/items/staff_of_wisdom.jpg'],
            ['name' => 'Helmet of the Brave', 'idWow' => 1006, 'imgPath' => 'images/items/helmet_of_the_brave.jpg'],
            ['name' => 'Boots of Swiftness', 'idWow' => 1007, 'imgPath' => 'images/items/boots_of_swiftness.jpg'],
            ['name' => 'Gloves of Precision', 'idWow' => 1008, 'imgPath' => 'images/items/gloves_of_precision.jpg'],
            ['name' => 'Cloak of Shadows', 'idWow' => 1009, 'imgPath' => 'images/items/cloak_of_shadows.jpg'],
            ['name' => 'Amulet of Protection', 'idWow' => 1010, 'imgPath' => 'images/items/amulet_of_protection.jpg'],
            ['name' => 'Belt of Strength', 'idWow' => 1011, 'imgPath' => 'images/items/belt_of_strength.jpg'],
            ['name' => 'Bracers of Agility', 'idWow' => 1012, 'imgPath' => 'images/items/bracers_of_agility.jpg'],
            ['name' => 'Chestplate of Fortitude', 'idWow' => 1013, 'imgPath' => 'images/items/chestplate_of_fortitude.jpg'],
            ['name' => 'Leggings of the Phoenix', 'idWow' => 1014, 'imgPath' => 'images/items/leggings_of_the_phoenix.jpg'],
            ['name' => 'Bow of the Eagle', 'idWow' => 1015, 'imgPath' => 'images/items/bow_of_the_eagle.jpg'],
            ['name' => 'Dagger of the Night', 'idWow' => 1016, 'imgPath' => 'images/items/dagger_of_the_night.jpg'],
            ['name' => 'Axe of Fury', 'idWow' => 1017, 'imgPath' => 'images/items/axe_of_fury.jpg'],
            ['name' => 'Hammer of Justice', 'idWow' => 1018, 'imgPath' => 'images/items/hammer_of_justice.jpg'],
            ['name' => 'Orb of Enlightenment', 'idWow' => 1019, 'imgPath' => 'images/items/orb_of_enlightenment.jpg'],
            ['name' => 'Tome of Knowledge', 'idWow' => 1020, 'imgPath' => 'images/items/tome_of_knowledge.jpg'],
            ['name' => 'Lantern of Hope', 'idWow' => 1021, 'imgPath' => 'images/items/lantern_of_hope.jpg'],
            ['name' => 'Trinket of Luck', 'idWow' => 1022, 'imgPath' => 'images/items/trinket_of_luck.jpg'],
            ['name' => 'Cape of the Wind', 'idWow' => 1023, 'imgPath' => 'images/items/cape_of_the_wind.jpg'],
            ['name' => 'Ring of Eternity', 'idWow' => 1024, 'imgPath' => 'images/items/ring_of_eternity.jpg'],
            ['name' => 'Necklace of the Sea', 'idWow' => 1025, 'imgPath' => 'images/items/necklace_of_the_sea.jpg'],
            ['name' => 'Sword of Flames', 'idWow' => 1026, 'imgPath' => 'images/items/sword_of_flames.jpg'],
            ['name' => 'Shield of Ice', 'idWow' => 1027, 'imgPath' => 'images/items/shield_of_ice.jpg'],
            ['name' => 'Potion of Mana', 'idWow' => 1028, 'imgPath' => 'images/items/potion_of_mana.jpg'],
            ['name' => 'Crown of Kings', 'idWow' => 1029, 'imgPath' => 'images/items/crown_of_kings.jpg'],
            ['name' => 'Scepter of the Sun', 'idWow' => 1030, 'imgPath' => 'images/items/scepter_of_the_sun.jpg'],
            ['name' => 'Boots of the Earth', 'idWow' => 1031, 'imgPath' => 'images/items/boots_of_the_earth.jpg'],
            ['name' => 'Gloves of the Moon', 'idWow' => 1032, 'imgPath' => 'images/items/gloves_of_the_moon.jpg'],
            ['name' => 'Helm of the Stars', 'idWow' => 1033, 'imgPath' => 'images/items/helm_of_the_stars.jpg'],
            ['name' => 'Armor of the Ancients', 'idWow' => 1034, 'imgPath' => 'images/items/armor_of_the_ancients.jpg'],
            ['name' => 'Sword of the Void', 'idWow' => 1035, 'imgPath' => 'images/items/sword_of_the_void.jpg'],
            ['name' => 'Shield of the Light', 'idWow' => 1036, 'imgPath' => 'images/items/shield_of_the_light.jpg'],
            ['name' => 'Potion of Speed', 'idWow' => 1037, 'imgPath' => 'images/items/potion_of_speed.jpg'],
            ['name' => 'Ring of Shadows', 'idWow' => 1038, 'imgPath' => 'images/items/ring_of_shadows.jpg'],
            ['name' => 'Amulet of the Forest', 'idWow' => 1039, 'imgPath' => 'images/items/amulet_of_the_forest.jpg'],
            ['name' => 'Bow of the Hunter', 'idWow' => 1040, 'imgPath' => 'images/items/bow_of_the_hunter.jpg'],
            ['name' => 'Dagger of Venom', 'idWow' => 1041, 'imgPath' => 'images/items/dagger_of_venom.jpg'],
            ['name' => 'Axe of the Berserker', 'idWow' => 1042, 'imgPath' => 'images/items/axe_of_the_berserker.jpg'],
            ['name' => 'Hammer of the Titans', 'idWow' => 1043, 'imgPath' => 'images/items/hammer_of_the_titans.jpg'],
            ['name' => 'Orb of the Mystic', 'idWow' => 1044, 'imgPath' => 'images/items/orb_of_the_mystic.jpg'],
            ['name' => 'Tome of Secrets', 'idWow' => 1045, 'imgPath' => 'images/items/tome_of_secrets.jpg'],
            ['name' => 'Lantern of the Abyss', 'idWow' => 1046, 'imgPath' => 'images/items/lantern_of_the_abyss.jpg'],
            ['name' => 'Trinket of the Gods', 'idWow' => 1047, 'imgPath' => 'images/items/trinket_of_the_gods.jpg'],
            ['name' => 'Cape of the Phoenix', 'idWow' => 1048, 'imgPath' => 'images/items/cape_of_the_phoenix.jpg'],
            ['name' => 'Ring of the Dragon', 'idWow' => 1049, 'imgPath' => 'images/items/ring_of_the_dragon.jpg'],
            ['name' => 'Necklace of the Sky', 'idWow' => 1050, 'imgPath' => 'images/items/necklace_of_the_sky.jpg'],
        ];
    }
}