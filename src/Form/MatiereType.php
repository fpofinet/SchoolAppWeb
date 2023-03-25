<?php

namespace App\Form;

use App\Entity\Filiere;
use App\Entity\Matiere;
use App\Entity\Enseignant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('libelle')
            ->add('filiere',EntityType::class, [
                'class' => Filiere::class,
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => false,
                ])
            ->add('enseignant',EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => function ($user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                    },
                'multiple' => false,
                'expanded' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
