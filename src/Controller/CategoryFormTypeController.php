<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryFormTypeController extends AbstractController
{
    /**
     * @Route("/category/form/type", name="app_category_form_type")
     */
    public function category(): Response
    {
        return $this->render('category_form_type.html.twig', [
            'controller_name' => 'categoryFormTypeController',
        ]);
    }
}
