<?php

namespace App\Controller;

use DateTime;
use App\Entity\Slider;
use App\Form\SliderFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SliderController extends AbstractController
{
    /**
    * @Route("/tableau-slider", name="show_slider", methods={"GET"})
    */
   public function showSlider(EntityManagerInterface $entityManager): Response
   {
       
       $sliders = $entityManager->getRepository(Slider::class)->findBy(['deletedAt' => null]);
       
       return $this->render("admin/show_slider.html.twig", [
           'sliders' => $sliders
       ]);
      
   }
   

    /**
     * @Route("/ajouter-un-slider", name="create_slider", methods={"GET|POST"})
     */
    public function createSlider(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        # 1 - Instanciation
        $slider = new Slider();

        # 2 - Création du formulaire
        $form = $this->createForm(SliderFormType::class, $slider)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $slider->setCreatedAt(new DateTime());
            $slider->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('photo')->getData();

            if($photo) {
                # Déconstructioon
                $extension = '.' . $photo->guessExtension();
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);


                # Reconstruction
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    $photo->move($this->getParameter('uploads_dir'), $newFilename);
                    $slider->setPhoto($newFilename);
                }
                catch(FileException $exception) {
                    # Code à exécuter en cas d'erreur.
                }
            } # end if($photo)

              

                $entityManager->persist($slider);
                $entityManager->flush();

                $this->addFlash('success', "Le slider est en ligne avec succès !");
                return $this->redirectToRoute('show_slider');

        } # end if ($form)

        # 3 - Création de la vue
        return $this->render("admin/form/slider.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
