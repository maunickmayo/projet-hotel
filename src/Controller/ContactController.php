<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="show_app_contact")
     */
    public function contact(): Response
    {
        return $this->render('contact/show_app_contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
