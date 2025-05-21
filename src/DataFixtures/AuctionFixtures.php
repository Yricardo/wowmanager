<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Auction;
use App\Entity\Price;
use App\Entity\Character;
use App\Entity\Item;

class AuctionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve references for characters and items
        $characters = [];
        for ($i = 1; $i <= 20; $i++) {
            $characters[] = $this->getReference('fcharacter_' . $i . '_1', Character::class);
        }

        $items = [];
        for ($i = 0; $i < 50; $i++) {
            $items[] = $this->getReference('item_' . $i, Item::class);
        }

        // Create auctions
        for ($i = 0; $i < 100; $i++) {
            $auction = new Auction();

            // Assign a random seller (character)
            $seller = $characters[array_rand($characters)];
            $auction->setSeller($seller);

            // Assign a random item
            $item = $items[array_rand($items)];
            $auction->setItem($item);

            // Set a random quantity
            $quantity = rand(1, 10);
            $auction->setQuantity($quantity);

            // Set a random price
            $price = new Price();
            $price->setGold($goldValue = rand(10, 500)); // Random gold value
            $price->setSilver(rand(0, 99)); // Random silver value
            $price->setBronze(rand(0, 99)); // Random copper value
            $auction->setPrice($price);

            $buyoutPrice = new Price();
            $buyoutPrice->setGold($goldValue + 100); // Buyout price is 100 po more than the auction price
            $buyoutPrice->setSilver(rand(0, 99)); // Random silver value
            $buyoutPrice->setBronze(rand(0, 99)); // Random copper value
            $auction->setMinimumBuyPrice($price); // Set minimum buy price to the same as the auction price

            $auction->setBuyoutPrice($buyoutPrice); // Set buyout price

            // Set auction metadata
            $auction->setCreationDate(new \DateTime());
            $auction->setDurationHour(rand(12, 72)); // Random duration between 12 and 72 hours
            $auction->setStatus('active'); // Default status
            $auction->setVisibility(Auction::VISIBILITY_GUILD); // Default visibility
            $auction->setOpenForBid(true); // Allow bidding
            $auction->setBuyout(true);
            $auction->setSoldQuantity(0); // Initially no items sold

            // Persist the auction
            $manager->persist($price); // Persist the price entity
            $manager->persist($auction);
        }

        // Flush all changes to the database
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CharacterFixtures::class,
            ItemFixtures::class,
        ];
    }
}