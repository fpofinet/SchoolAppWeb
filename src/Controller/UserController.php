<?php

namespace App\Controller;

use App\Entity\Eleve;
use Faker\Factory;
use App\Entity\User;
use App\Form\NoteType;
use App\Form\UserType;
use App\Entity\Enseignant;
use App\Entity\Scolarite;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $users=$doctrine->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'eleves'=>$users
        ]);
    }

    /**
     * @Route("/user/add",name="add_user")
     */
    public function addAndUpdate(User $user=null,Request $request,ManagerRegistry $doctrine,UserPasswordHasherInterface $encoder):Response
    {
        if($user==null){
            $user=new User();
        }
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash= $encoder->hashPassword($user,$user->getPassword());
            $user->setPassword($hash);      
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_user");
        }
        return $this->renderForm('user/form.html.twig', [
            'form'=>$form
        ]);
    }

     /**
     * @Route("/user/dump", name="test")
     */
    public function test(ManagerRegistry $doctrine,UserPasswordHasherInterface $encoder,Request $request): Response
    {
        $eleves=$doctrine->getRepository(Eleve::class)->findAll();
        foreach($eleves as $e){
            $sco=new Scolarite();
            $sco->setTotal(0);
            $sco->setEleve($e);
            $doctrine->getManager()->persist($sco);
        }
        $doctrine->getManager()->flush();
        return $this->renderForm('home/index.html.twig', [
            
        ]);
    }
}
