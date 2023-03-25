<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{
    /**
     * @Route("/salle", name="app_salle")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo=new SalleRepository($doctrine);
        $d=$repo->getFull();
        ///dd($d);
        return $this->render('salle/index.html.twig', [
             'salles' => $d,
        ]);
    }

    /**
     * @Route("/salle/{id}/update",name="update_salle")
     * @Route("/salle/new",name="add_salle")
     */
    public function addAndUpdateSalle(Salle $salle=null,Request $request,ManagerRegistry $doctrine):Response
    {
        if($salle ==null){
            $salle= new Salle();
        }
        $form = $this->createForm(SalleType::class,$salle);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form["filiere"]->getData()->getId());
            $filiere=$form["filiere"]->getData();
            $filiere->setSalle($salle);
            $doctrine->getManager()->persist($salle);
            $doctrine->getManager()->persist($filiere);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_salle");
        }
        return $this->renderForm('salle/form.html.twig', [
            'form' => $form,
            'editState'=> $salle->getId() !==null
        ]);
    }
    /**
     * @Route("/salle/{id}/delete",name="delete_salle")
     */
    public function deleteSalle(Salle $salle=null,ManagerRegistry $doctrine):Response
    {
        if($salle !=null){
            $doctrine->getManager()->remove($salle);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_salle");
        }
        return $this->redirectToRoute("app_salle");
    }
}
