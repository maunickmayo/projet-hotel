<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Entity\Chambre;
use App\Form\ChambreFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/admin")  
 */

class AdminController extends AbstractController
{


    /**
     * @Route("/tableau-membre", name="show_dashboard", methods={"GET"})
     */
    public function showDashboardMembre(EntityManagerInterface $entityManager): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } catch (AccessDeniedException $exception) {
            $this->addFlash('warning', 'Cette partie du site est réservée aux admins');
            return $this->redirectToRoute('default_home');
        }
        
        $membres = $entityManager->getRepository(Membre::class)->findBy(['deletedAt' => null]);
        
        return $this->render("admin/show_dashboard.html.twig", [
            'membres' => $membres
        ]);

       
    }
    
   
    /**
     * @Route("/ajouter-une-chambre", name="create_chambre", methods={"GET|POST"})
     */
    public function createChambe(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
       
        $chambre = new Chambre();

       
        $form = $this->createForm(ChambreFormType::class, $chambre)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $chambre->setCreatedAt(new DateTime());
            $chambre->setUpdatedAt(new DateTime());

         

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

          
            if($photo) {
               
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
//                $safeFilename = $article->getAlias();

               
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $chambre->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                    
                }
            } 

                

                $entityManager->persist($chambre);
                $entityManager->flush();

                $this->addFlash('success', "La chambre a ete ajoutée avec succès !");
                return $this->redirectToRoute('show_chambre');

        } 

       
        return $this->render("admin/form/chambre.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modifier-une-chambre_{id}", name="update_chambre", methods={"GET|POST"})
     */
    public function updateArticle(Chambre $chambre, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $originalPhoto = $chambre->getPhoto();

       
        $form = $this->createForm(ChambreFormType::class, $chambre, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $chambre->setUpdatedAt(new DateTime());

            

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

           
            if($photo) {

              
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
//                $safeFilename = $article->getAlias();

               
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $chambre->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                   
                }
            } else {
                $chambre->setPhoto($originalPhoto);
            } 

          

            $entityManager->persist($chambre);
            $entityManager->flush();

            $this->addFlash('success', "La chambre a été modifié avec succès !");
            return $this->redirectToRoute('chambre/show_chambre');
        } # end if ($form)

        
        return $this->render("admin/form/chambre.html.twig", [
            'form' => $form->createView(),
            'chambre' => $chambre
        ]);
    }


    

    /**
     * @Route("/supprimer-une-chambre_{id}", name="hard_delete_chambre", methods={"GET"})
     */
    public function hardDeleteChambre(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
    {
        
        $photo = $chambre->getPhoto();

     
        if($photo) {
            unlink($this->getParameter('uploads_dir'). '/' . $photo);
        }

        $entityManager->remove($chambre);
        $entityManager->flush();

        $this->addFlash('success', "La chambre a bien été supprimé de la base de données");
        return $this->redirectToRoute('chambre/show_chambre');
    }
}
