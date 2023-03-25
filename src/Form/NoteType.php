<?php

namespace App\Form;

use App\Entity\User;
use App\Controller\UtilsController;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  
       // $utils= new  UtilsController();
        $lists=[1 => "anna", 2 => "vginie", 3 => "lucie"];
       // dd($lists);
        foreach($lists as $l){
            $builder->add($l, TextType::class, [
                'label' => $l,
                'mapped' => false,
                'required' => false
            ]);
        }
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    private function getData(){
        $doctrine = new ManagerRegistry();
        $users= $doctrine->getRepository(User::class)->findAll();
        return $users;
    }
}
