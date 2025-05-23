<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SettingController extends AbstractController 
{
    #[Route('/member/settings', name: 'app_member_settings', methods: ['GET', 'POST'])]
    public function memberSettings(Request $request, SettingManager $manager): Response
    {
        $dataObject = $manager->generateFormDataObject();

        $form = $this->createForm(SettingsType::class, $event);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->updateSettingsFromDataObject($dataObject);
        }
        
        return $this->render('member/settings.html.twig', ['form' => $form->createView()]);
    }
}