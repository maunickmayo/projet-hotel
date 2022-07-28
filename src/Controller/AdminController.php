<?php

namespace App\Controller;

use DateTime;
use App\Entity\Membre;
use App\Entity\Chambre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


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
     * @Route("/ajouter-un-chambre", name="create_chambre", methods={"GET|POST"})
     */
    public function createChambre(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
      
        $chambre = new Chambre();
   
   

$form = $this->createForm(ChambreFormType::class, $chambre)->handleRequest($request);


if($form->isSubmitted() && $form->isValid()) {

$chambre->setCreatedAt(new DateTime());
$chambre->setUpdatedAt(new DateTime());


//$chambre->setAlias($slugger->slug($chambre->getTitle()));

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
}  # end if($photo)


    //$chambre->setAuthor($this->getUser());

    $entityManager->persist($chambre);
    $entityManager->flush();

    $this->addFlash('success', "La chambre est en ligne avec succès !");
    return $this->redirectToRoute('show_dashboard');

} # end if ($form)


return $this->render("admin/form/chambre.html.twig", [
    'form' => $form->createView()
]);

}# end function createArticle
/**
* @Route("/modifier-une-chambre_{id}", name="update_chambre", methods={"GET|POST"})
*/
public function updateChambre(Chambre $chambre, Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
{  
    $originalPhoto = $chambre->getPhoto();


$form = $this->createForm(ChambreFormType::class, $chambre, [
'photo' => $originalPhoto
])->handleRequest($request);

if($form->isSubmitted() && $form->isValid()) {

$chambre->setUpdatedAt(new DateTime());


//$chambre->setAlias($slugger->slug($chambre->getTitle()));

/** @var UploadedFile $photo */
$photo = $form->get('photo')->getData();


if($photo) {
    $extension = '.' . $photo->guessExtension();
    $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
    $safeFilename = $slugger->slug($originalFilename);
    $newFilename = $safeFilename . '_' . uniqid() . $extension;

    try {
        $photo->move($this->getParameter('uploads_dir'), $newFilename);
        $chambre->setPhoto($newFilename);
    }
    catch(FileException $exception) {
       
    }
} else {
    $chambre->setPhoto($originalPhoto);
} # end if($photo)
//$chambre->setAuthor($this->getUser());
$entityManager->persist($chambre);
$entityManager->flush();

$this->addFlash('success', "La chambre a été modifié avec succès !");
return $this->redirectToRoute('show_dashboard');
} # end if ($form)
return $this->render("admin/form/chambre.html.twig", [
'form' => $form->createView(),
'chambre' => $chambre
]);

}# end function updateChambre

/**
* @Route("/archiver-une chambre_{id}", name="soft_delete_chambre", methods={"GET"})
*/
public function softDeleteArticle(Chambre $chambre, EntityManagerInterface $entityManager): Response
{
$chambre->setDeletedAt(new DateTime());

$entityManager->persist($chambre);
$entityManager->flush();

$this->addFlash('success', "La chambre a bien été archivé");
return $this->redirectToRoute('show_dashboard');
}# end function softDelete

/**
* @Route("/restaurer-une chambre_{id}", name="restore_chambre", methods={"GET"})
*/
public function restoreChambre(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
{
$chambre->setDeletedAt(null);

$entityManager->persist($chambre);
$entityManager->flush();

$this->addFlash('success', "La chambre a bien été restauré");
return $this->redirectToRoute('show_dashboard');
}

/**
* @Route("/voir-les-chambres-archives", name="show_trash", methods={"GET"})
*/
public function showTrash(EntityManagerInterface $entityManager): Response
{
$archivedChambre = $entityManager->getRepository(Chambre::class)->findByTrash();

return $this->render("admin/trash/chambre_trash.html.twig", [
'archivedChambres' => $archivedChambre
]);
}

/**
* @Route("/supprimer-une chambre_{id}", name="hard_delete_chambre", methods={"GET"})
*/
public function hardDeleteArticle(Chambre $chambre, EntityManagerInterface $entityManager): RedirectResponse
{

$photo = $chambre->getPhoto();


if($photo) {
unlink($this->getParameter('uploads_dir'). '/' . $photo);
}

$entityManager->remove($chambre);
$entityManager->flush();

$this->addFlash('success', "La chambre a bien été supprimé de la base de données");
return $this->redirectToRoute('show_trash');
}

}