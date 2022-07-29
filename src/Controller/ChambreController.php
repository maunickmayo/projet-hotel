<?php

namespace App\Controller;

use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
   
        /**
     * @Route("/chambre", name="show_chambre")
     */
    public function showChambre(EntityManagerInterface $entityManager): Response
    {
        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt'=> null]); 
        
        return $this->render("admin/show_chambre.html.twig", [
           'chambres' => $chambres
        ]);
        
    }
    
   
}

