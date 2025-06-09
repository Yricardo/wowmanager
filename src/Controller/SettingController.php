<?php

namespace App\Controller;

use App\Form\Settings\SettingsType;
use App\Managers\SettingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SettingController extends AbstractController
{
    #[Route('/member/settings', name: 'app_member_settings', methods: ['GET', 'POST'])]
    public function memberSettings(Request $request, SettingManager $manager): Response
    {
        $form = $this->createForm(SettingsType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->updateSettingsFromSettingType($this->getUser(), $form->getData());
        }

        return $this->render('member/views/settings.html.twig', ['form' => $form->createView()]);
    }
}
