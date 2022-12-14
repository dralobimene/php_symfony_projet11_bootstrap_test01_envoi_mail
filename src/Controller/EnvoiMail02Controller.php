<?php

namespace App\Controller;

use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnvoiMail02Controller extends AbstractController
{
    #[Route('/envoi/mail02', name: 'app_envoi_mail02')]
    public function envoiMailParService(SendMailService $mail): Response
    {

        // on utilise le service pr envoyer le mail
        $mail->send(
            'test@testService.fr', // from
            'lolo@test.fr', // to
            'le SUJET du mail', // subject
            'registration', // le nom du template twig (qui contiendra le corps du mail)
                            // sa localisation et ses extensions st definis ds le service
        );
        return $this->render('envoi_mail02/envoi_mail02.html.twig', [
            'controller_name' => 'EnvoiMail02Controller',
        ]);
    }
}
