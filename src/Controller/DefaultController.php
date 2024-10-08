<?php

namespace App\Controller;

use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

   /**
    * @Route("/", name="default_home", methods={"GET"})
    */ 
   public function home(EntityManagerInterface $entityManager): Response
   {
        $chambre = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);
        return $this->render("default/home.html.twig",[
         'chambres' => $chambre
        ]);
   }
}
