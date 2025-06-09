<?php

namespace App\DataFixtures;

use App\Entity\WowVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WowVersionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $wowVersion = new WowVersion();
        $wowVersion->setName('Classic');
        $manager->persist($wowVersion);
        $this->addReference(WowVersion::WOW_VERSION_CLASSIC, $wowVersion);
        $manager->flush();

        $wowVersion = new WowVersion();
        $wowVersion->setName('Classic era');
        $manager->persist($wowVersion);
        $this->addReference(WowVersion::WOW_VERSION_CLASSIC_ERA, $wowVersion);
        $manager->flush();

        $wowVersion = new WowVersion();
        $wowVersion->setName('Retail');
        $manager->persist($wowVersion);
        $this->addReference(WowVersion::WOW_VERSION_RETAIL, $wowVersion);
        $manager->flush();

        // we can symplify this by using a loop and an array of names
        $wowVersions = [
            'Classic',
            'Classic era',
            'Retail',
        ];
        foreach ($wowVersions as $versionName) {
            $wowVersion = new WowVersion();
            $wowVersion->setName($versionName);
            $manager->persist($wowVersion);
            $this->addReference('wow_version_'.strtoupper(str_replace(' ', '_', $versionName)), $wowVersion);
        }
    }
}
