<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Form\FiliereType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FiliereController extends AbstractController
{
    /**
     * @Route("/filiere", name="app_filiere")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $filieres=$doctrine->getRepository(Filiere::class)->findAll();
        return $this->render('filiere/index.html.twig', [
            'filieres' => $filieres,
        ]);
    }

    /**
     * @Route("/filiere/{id}/update",name="update_filiere")
     * @Route("/filiere/new",name="add_filiere")
     */
    public function addAndUpdateFiliere(Filiere $filiere=null,Request $request,ManagerRegistry $doctrine):Response
    {
        if($filiere ==null){
            $filiere= new Filiere();
        }
        $form = $this->createForm(FiliereType::class,$filiere);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($filiere->getId()==null){
                $filiere->setEffectif(0);
            }
            $doctrine->getManager()->persist($filiere);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_filiere");
        }
        return $this->renderForm('filiere/form.html.twig', [
            'form' => $form,
            'editState'=> $filiere->getId() !==null
        ]);
    }
    /**
     * @Route("/filiere/{id}/delete",name="delete_filiere")
     */
    public function deleteFiliere(Filiere $filiere=null,ManagerRegistry $doctrine):Response
    {
        if($filiere !=null){
            $doctrine->getManager()->remove($filiere);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_filiere");
        }
        return $this->redirectToRoute("app_filiere");
    }
}
