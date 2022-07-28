<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="show_app_avis")
     */
    public function avis(): Response
    {
        return $this->render('avis/show_app_avis.html.twig', [
            'controller_name' => 'AvisController',
        ]);
    }
}
