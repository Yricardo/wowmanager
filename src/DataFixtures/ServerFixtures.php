<?php

namespace App\DataFixtures;

use App\Entity\Server;
use App\Entity\WowVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ServerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $versions = [
            WowVersion::WOW_VERSION_CLASSIC => [
                'Mirage raceway',
                'Atiesh',
            ],
            WowVersion::WOW_VERSION_RETAIL => [
                'Ner’zhul',
            ],
        ];

        foreach ($versions as $key => $serverNames) {
            foreach ($serverNames as $serverName) {
                $server = new Server();
                $server->setName($serverName);
                $server->setWowVersion($this->getReference($key, WowVersion::class));
                $manager->persist($server);
            }
            $this->addReference($key, $server);
        }

        for ($index = 0; $index < count($serverNames); ++$index) {
            $server = new Server();
            $serverName = $serverNames[$index];
            $server->setName($serverName);
            $server->setWowVersion($this->getReference(WowVersion::WOW_VERSION_CLASSIC, WowVersion::class));
            $manager->persist($server);
            $manager->flush();
        }

        $this->addReference('server', $server);
    }

    public function getDependencies(): array
    {
        return [
            WowVersionFixtures::class,
        ];
    }
}
