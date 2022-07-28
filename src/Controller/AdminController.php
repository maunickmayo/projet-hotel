<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin")  
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/tableau-membre", name="show_dashboard", methods={"GET"})
     */
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        
        $membres = $entityManager->getRepository(Membre::class)->findBy(['deletedAt' => null]);
        
        return $this->render("admin/show_dashboard.html.twig", [
            'membres' => $membres
        ]);

       
    
       
    }
    
    /**
     * @Route("/ajouter-un-article", name="create_article", methods={"GET|POST"})
     */
    public function createChambre(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
      
        $chambre = new Chambre();
    }
   
}