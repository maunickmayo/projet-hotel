<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualiteController extends AbstractController
{
    /**
     * @Route("/actualite", name="show_app_actualite")
     */
    public function actualite(): Response
    {
        return $this->render('actualite/show_app_actualite.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
}
