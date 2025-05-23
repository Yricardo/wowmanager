<?php

namespace App\Form\Settings;
use App\Form\DataObject\Settings\SettingsDataObject;
use App\Managers\SettingManager;

class SettingFormHelper 
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SettingRepository $repository
    ) {
    }

    public function generateDto(): SettingsDataObject
    {
        return (new SettingsDataObject())->setSettings($this->repository->findAll());
    }
}