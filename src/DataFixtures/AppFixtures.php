<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $manager;
    private $encoder;
    private $faker;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->faker = Factory::create("fr_FR");
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadUser();

        $manager->flush();
    }
    
    /**
     * Créations des utilisateurs 
     *
     * @return void
     */
    public function loadUser()
    {
        for($i = 1; $i<10; $i++){
            $user = new User();

            $user->setFirstname($this->faker->firstNameMale())
                ->setLastname($this->faker->lastName())
                ->setUsername('user'.$i);                       

            $user->setEmail($user->getFirstname().'.'.$user->getLastname().'@test.fr');


            $hash = $this->encoder->encodePassword($user, $user->getUsername());
            $user->setPassword($hash)
                 ->setCreatedAt(new \DateTime())
                 ->setUpdatedAt(new \DateTime())
                 ->setCreatedUser(1)
                 ->setUpdatedUser(1);

            $this->addReference("employe ".$i, $user);

            $this->manager->persist($user);
        } 
        
    }
}
