<?php

namespace App\Controller;

use App\Entity\Chambre;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    /**
    * @Route("/tableau-chambre", name="show_chambre", methods={"GET"})
    */
    public function showChambres(EntityManagerInterface $entityManager): Response
    {
        
        $chambres = $entityManager->getRepository(Chambre::class)->findBy(['deletedAt' => null]);
        
        return $this->render("show_chambre", [
            'chambres' => $chambres
        ]);
    }

    /**
     * @Route("/voir-chambre", name="show_chambre_from_commande", methods={"GET"})
     */
    public function showChambresFromCommande(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $chambres = $entityManager->getRepository(Chambre::class)
            ->findBy([
                'commande' => $commande->getId(),
                'deletedAt' => null
            ]);

        return $this->render("chambre/show_chambre_from_commande.html.twig", [
            'chambres' => $chambres,
            'commande' => $commande
        ]);
    }

}
