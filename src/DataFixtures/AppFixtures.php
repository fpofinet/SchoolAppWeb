<?php

namespace App\DataFixtures;

use App\Entity\Filiere;
use Faker\Factory;
use App\Entity\eleve;
use App\Entity\Salle;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        //$encoder =new UserPasswordHasherInterface();
        for($i=1;$i<20;$i++){
            $salle = new Salle();
            $salle->setCode("SO".$i);
            $salle->setNbrePlace(45);
            $manager->persist($salle);
        }
        //user Dump
        for($j=0;$j<6;$j++){
            $filiere = new Filiere();
            $filiere->setCode("filiere ".($j+1));
            $filiere->setLibelle("filiere ".($j+1)." ");
            //$filiere->setEffectif(0);
            
            for($i=0;$i<20;$i++){
                $eleve = new Eleve();
                $eleve->setEmail($faker->lastName().$i.$j."@mail.com");
                $eleve->setNom($faker->lastName());
                $eleve->setPrenom($faker->firstName());
                $eleve->setContact($faker->phoneNumber());
                $eleve->setAdresse($faker->address());
                $eleve->setDateNaiss($faker->dateTimeBetween($startDate = '-25 years', $endDate = 'now', $timezone = null));
                $eleve->setPassword("123456");
                $eleve->setFiliere($filiere);
                $filiere->setEffectif($filiere->getEffectif()+1);
                if($i%2==0){
                    $eleve->setSexe("M");
                } else{
                    $eleve->setSexe("F");
                }
    
                $manager->persist($eleve);
            }
            $manager->persist($filiere);
        }
        $manager->flush();
    }
}
