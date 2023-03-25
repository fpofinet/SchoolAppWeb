<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\Eleve;
use App\Entity\Filiere;
use App\Entity\Matiere;
use App\Repository\NoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NoteController extends AbstractController
{
    /**
     * @Route("/note", name="app_note")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $filieres= $doctrine->getRepository(Filiere::class)->findAll();
        return $this->render('note/index.html.twig', [
            'filieres'=>$filieres
        ]);
    }
    /**
     * @Route("/note/{code}",name="intermed")
     */
    public function intermed(Filiere $filiere):Response
    {
        if($filiere !=null){
            return $this->render('note/matiereList.html.twig', [
                'matieres'=>$filiere->getMatieres(),
                'filiere'=>$filiere->getCode()
            ]);
        } else{
            return $this->render('note/index.html.twig');
        }
    }
    /**
     * @Route("/note/{code}/{codem}",name="add_note")
     */
    public function addNote(Filiere $filiere,$codem,ManagerRegistry $doctrine,Request $request):Response
    {
        //recuperation de la matiere
        $matiere = $doctrine->getRepository(Matiere::class)->findOneBy(["code"=>$codem]);
        
        //recuperation de la liste des eleves
        $users=$doctrine->getRepository(Eleve::class)->findBy(["filiere"=>$filiere]);
       
        //creation du formulaire d'ajout des notes
        $formBuilder=$this->createFormBuilder();
        $formBuilder->add('type', ChoiceType::class, [
            'choices'  => [
                'Controle' => "Controle",
                'Examen' => "Examen",
                'Rattrapage' => "Rattrapage",
            ],
            'label'=>'Note De',
            'mapped' => false,
            'required' => true,
        ]);
        foreach($users as $l){
            $formBuilder->add($l->getId(), TextType::class, [
                'label' => $l->getNom().' '.$l->getPrenom(),
                'mapped' => false,
                'required' => false,
            ]);
        }
       
        $form=$formBuilder->getForm();
        $form->handleRequest($request);

        //traitement du formulaire
        if($form->isSubmitted() && $form->isValid()){
            //on verifie si il ya pas deja des notes enregistre pour cette matiere dans cette filiere
            $data=$this->findNoteByFiliere($matiere,$users,$doctrine);

            //si les notes existe alors on fait une mise à jour des note
            //sinon on creer des nouvelles notes pour cette matiere dans cette filiere
            if($data !=null){
                if($form["type"]->getData()=="Examen"){
                    $this->updateNoteExamen($data,$form,$doctrine);
                } elseif($form["type"]->getData()=="Controle"){
                    $this->updateNoteControle($data,$form,$doctrine);
                } else{
                    $this->updateNoteRattrapage($data,$form,$doctrine);
                } 
            } else{
                foreach($form as $d){
                    if($d !=$form["type"]){
                        $note = new Note();
                        $user=$doctrine->getRepository(User::class)->findOneBy(["id"=>$d->getName()]);
                        $note->setEleve($user);
                        $note->setMatiere($matiere);
                        $note->setNote(0);
                        $note->setDevoir(0);
                        $note->setRattrapage(0);
        
                        if($d->getData() != null){
                            if($form["type"]->getData()=="Examen"){
                                $note->setNote((float)$d->getData());
                            } elseif($form["type"]->getData()=="Controle"){
                                $note->setDevoir((float)$d->getData());
                            } else{
                                $note->setRattrapage((float)$d->getData());
                            } 
                        }
                        $doctrine->getManager()->persist($note);
                    }
                }
                $doctrine->getManager()->flush();
            }
            
            return $this->redirectToRoute("app_home");
        }
        return $this->renderForm('note/addNote.html.twig', [
           'form' =>$form
        ]);
    }

    /**
     * cette methode permet de verifier si il existe des notes pour
     * une matiere dans une filiere donnée
     * elle revoit un tableau contenant les notes concernées ou null
     * il les notes n'existe pas
    */
    private function findNoteByFiliere($matiere,$eleves,$manager): ?array
    {
        $notes=$manager->getRepository(Note::class)->findBy(["matiere"=>$matiere]);
        $output=array();
        foreach($notes as $n){
            foreach($eleves as $e){
                if($n->getEleve()->getId()==$e->getId() ){
                    $output[]=$n;
                }
            }
        }
        if(Count($output)==0){
            return null;
        } else{
            return $output;
        }
       
    }


    /** 
     * les trois methodes suivantes permettent de mettre a jour respectivement les notes
     * de controles, d'examen et de controle
     */
    private function updateNoteControle($old,$new,$manager)
    {
        if($new->getName() != "type"){
            foreach($old as $o){
                foreach($new as $n){
                    if($o->getEleve()->getId()==$n->getName()){
                        $o->setDevoir((float)$n->getData());
                        $manager->getManager()->persist($o);
                    }
                }
            }
        }
        $manager->getManager()->flush();
    }

    private function updateNoteExamen($old,$new,$manager)
    {
        if($new->getName() != "type"){
            foreach($old as $o){
                foreach($new as $n){
                    if($o->getEleve()->getId()==$n->getName()){
                        $o->setNote((float)$n->getData());
                        $manager->getManager()->persist($o);
                    }
                }
            }
        }
        $manager->getManager()->flush();
    }

    private function updateNoteRattrapage($old,$new,$manager)
    {
        if($new->getName() != "type"){
            foreach($old as $o){
                foreach($new as $n){
                    if($o->getEleve()->getId()==$n->getName()){
                        $o->setRattrapage((float)$n->getData());
                        $manager->getManager()->persist($o);
                    }
                }
            }
        }
        $manager->getManager()->flush();
    }
}
