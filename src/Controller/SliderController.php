<?php

namespace App\Controller;

use App\Entity\Slider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SliderController extends AbstractController
{ /**
    * @Route("/tableau-slider", name="show_slider", methods={"GET"})
    */
   public function showSlider(EntityManagerInterface $entityManager): Response
   {
       
       $sliders = $entityManager->getRepository(Slider::class)->findBy(['deletedAt' => null]);
       
       return $this->render("admin/show_slider.html.twig", [
           'sliders' => $sliders
       ]);
      
   }
   
}
