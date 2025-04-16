<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Guild;
use App\Entity\Character;
use App\Entity\Server;

class GuildFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $guildNames = [
            'the happy guild',
            'pvp beefs',
            'elyseum',
            'the asshole skilled elitists',
            'the cool guys'
        ];

        // Loop through guild names and create a new guild for each name
        for ($index = 0; $index < count($guildNames); $index++) {
            $guildName = $guildNames[$index];
            $guild = new Guild();
            $guild->setName($guildName);
            $guild->setServer($this->getReference('server', Server::class));
            $manager->persist($guild);
            $this->addReference('guild_' . $guildName, $guild);
        }
        
        $manager->flush();

        // Loop through characters stored in references and assign one of the guilds to each character
        for ($i = 1; $i <= 20; $i++) {
            $character = $this->getReference('fcharacter_' . $i . '_1', character::class);
            $guild = $this->getReference('guild_' . $guildNames[rand(0, count($guildNames) - 1)], Guild::class);
            $character->setGuild($guild);
            $manager->persist($character);
        }

        for ($i = 1; $i <= 20; $i++) {
            $character = $this->getReference('scharacter_' . $i, character::class);
            $guild = $this->getReference('guild_' . $guildNames[rand(0, count($guildNames) - 1)], Guild::class);
            $character->setGuild($guild);
            $manager->persist($character);
        }

        $manager->flush();
    }

    public function getDependencies(): array
	{
		return [
			CharacterFixtures::class
		];
	}
}
