<?php

namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EnseignantController extends AbstractController
{
    /**
     * @Route("/enseignant", name="app_enseignant")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $enseignants=$doctrine->getRepository(Enseignant::class)->findAll();
        return $this->render('enseignant/index.html.twig', [
            'enseignants'=>$enseignants
        ]);
    }

    /**
     * @Route("/enseignant/{id}/update",name="update_enseignant")
     * @Route("/enseignant/add",name="add_enseignant")
     */
    public function addAndUpdate(Enseignant $enseignant=null,Request $request,ManagerRegistry $doctrine,UserPasswordHasherInterface $encoder):Response
    {
        if($enseignant==null){
            $enseignant=new Enseignant();
        }
        $form= $this->createForm(EnseignantType::class,$enseignant);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash= $encoder->hashPassword($enseignant,$enseignant->getPassword());
            $enseignant->setPassword($hash);      
            $doctrine->getManager()->persist($enseignant);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_enseignant");
        }
        return $this->renderForm('enseignant/form.html.twig', [
            'form'=>$form,
            'editState' => $enseignant->getId()!==null
        ]);
    }
}
