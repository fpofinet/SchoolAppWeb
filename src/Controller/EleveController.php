<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Eleve;
use App\Entity\Scolarite;
use App\Form\EleveType;
use DateTime;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EleveController extends AbstractController
{
    /**
     * @Route("/eleve", name="app_eleve")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $eleves=$doctrine->getRepository(Eleve::class)->findAll();
        return $this->render('eleve/index.html.twig', [
            'eleves'=>$eleves
        ]);
    }
    /**
     * @Route("/eleve/{id}/info",name="info_eleve")
     */
    public function infoEleve(Eleve $eleve=null):Response
    {
        if($eleve !=null){
            $now=new DateTimeImmutable();
            $age=$eleve->getDateNaiss()->diff($now);
            return $this->render('eleve/details.html.twig', [
                'eleve'=>$eleve,
                'age'=>$age->format('%Y')
            ]);
        } else{
            return $this->redirectToRoute("app_eleve");
        }
    }
    /**
     * @Route("/eleve/{id}/update",name="update_eleve")
     * @Route("/eleve/add",name="add_eleve")
     */
    public function addAndUpdate(Eleve $eleve=null,Request $request,ManagerRegistry $doctrine,UserPasswordHasherInterface $encoder):Response
    {
        if($eleve==null){
            $eleve=new Eleve();
        }
        $form= $this->createForm(EleveType::class,$eleve);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($eleve->getId()== null){
                $sco= new Scolarite();
                $sco->setTotal(0);
                $eleve->setScolarite($sco);
            }
            $hash= $encoder->hashPassword($eleve,$eleve->getPassword());
            $eleve->setPassword($hash);      
            $doctrine->getManager()->persist($eleve);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_eleve");
        }
        return $this->renderForm('eleve/form.html.twig', [
            'form'=>$form,
            'editState'=>$eleve->getId() !==null
        ]);
    }
    /**
     * @Route("/bulletin/{id}",name="bulletin")
     */
    public function bulletin(Eleve $eleve,ManagerRegistry $doctrine):Response
    {
        $notes = $doctrine->getRepository(Note::class)->findBy(["eleve" => $eleve]);
        return $this->render('eleve/bulletin.html.twig', [
           'notes'=>$notes
        ]);
    }
}
