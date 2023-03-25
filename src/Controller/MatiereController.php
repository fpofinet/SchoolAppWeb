<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MatiereController extends AbstractController
{
    /**
     * @Route("/matiere", name="app_matiere")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $matieres = $doctrine->getRepository(Matiere::class)->findAll();
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    /**
     * @Route("/matiere/{id}/update",name="update_matiere")
     * @Route("/matiere/new",name="add_matiere")
     */
    public function addAndUpdateMatiere(Matiere $matiere=null,Request $request,ManagerRegistry $doctrine):Response
    {
        if($matiere ==null){
            $matiere= new Matiere();
        }
        $form = $this->createForm(MatiereType::class,$matiere);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $doctrine->getManager()->persist($matiere);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_matiere");
        }
        return $this->renderForm('matiere/form.html.twig', [
            'form' => $form,
            'editState'=> $matiere->getId() !==null
        ]);
    }

    /**
     * @Route("/matiere/{id}/delete",name="delete_matiere")
     */
    public function deleteMatiere(Matiere $matiere=null,ManagerRegistry $doctrine):Response
    {
        if($matiere !=null){
            $doctrine->getManager()->remove($matiere);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_matiere");
        }
        return $this->redirectToRoute("app_matiere");
    }
}
