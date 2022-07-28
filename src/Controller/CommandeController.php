<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
     /**
    * @Route("/tableau-commande", name="show_commande", methods={"GET"})
    */
    public function showCommande(EntityManagerInterface $entityManager): Response
    {
        
        $commandes = $entityManager->getRepository(Commande::class)->findBy(['deletedAt' => null]);
        
        return $this->render("commande/show_commande.html.twig", [
            'commandes' => $commandes
        ]);
    }

     /**
     * @Route("/ajouter-une-categorie", name="create_category", methods={"GET|POST"})
     */
    public function createCommande(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeFormType::class, $commande)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            
            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());

            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', "La commandea bien été ajoutée");
            return $this->redirectToRoute('show_commande');
        }

        return $this->render("admin/form/commande.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modifier-une-categorie/{id}", name="update_category", methods={"GET|POST"})
     */
    public function updateCommande(Commande $commande, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CommandeFormType::class, $commande)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

           
            $commande->setUpdatedAt(new DateTime());

            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', "La commande a bien été modifiée");
            return $this->redirectToRoute('show_commande');
        }

        return $this->render("admin/form/commande.html.twig", [
            'form' => $form->createView(),
            'commande' => $commande
        ]);
    }

   
    /**
     * @Route("/supprimer-une-categorie/{id}", name="hard_delete_category", methods={"GET"})
     */
    public function hardDeleteCategory(Commande $commande, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($commande);
        $entityManager->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimé définitivement de la base');
        return $this->redirectToRoute('commnde/show_commande.html.twig');
    }





}



