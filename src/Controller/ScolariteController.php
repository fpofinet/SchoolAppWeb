<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Filiere;
use App\Entity\Tranche;
use App\Form\ScolariteType;
use App\Repository\EleveRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ScolariteController extends AbstractController
{
    /**
     * @Route("/scolarite", name="app_scolarite")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $filieres=$doctrine->getRepository(Filiere::class)->findAll();
        return $this->render('scolarite/index.html.twig', [
            'filieres'=>$filieres
        ]);
    }
    /**
     * @Route("/scolarite/{code}", name="tmp_sco")
     */
    public function tmp_sco(Filiere $filiere=null): Response
    {
        if($filiere !=null){
            return $this->render('scolarite/l.html.twig', [
                'eleves'=>$filiere->getEleves()
            ]);
        } else{
            return $this->redirectToRoute("app_scolarite");
        }
       
    }
    /**
     * @Route("/scolarite/{id}/payer",name="payer")
     */
    public function payerScolarite(Eleve $eleve=null,ManagerRegistry $doctrine,Request $request):Response
    {
        $tranche= new Tranche();
        $form= $this->createForm(ScolariteType::class,$tranche);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $tranche->setCreatedAt(new \DateTimeImmutable());
            $tranche->setScolarite($eleve->getScolarite());
            
            $eleve->getScolarite()->SetTotal($eleve->getScolarite()->getTotal()+$tranche->getMontant());
            $doctrine->getManager()->persist($tranche);
            $doctrine->getManager()->persist($eleve);
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("app_home");
        }
        return $this->renderForm('scolarite/form.html.twig', [
            'form'=>$form
        ]);
    }
}
