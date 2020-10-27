<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Employee;
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
        $this->loadEmployee();

        $manager->flush();
    }
    
    /**
     * Créations des utilisateurs 
     *
     * @return void
     */
    public function loadEmployee()
    {
        for($i = 1; $i<10; $i++){
            $employee = new Employee();

            $employee->setFirstname($this->faker->firstNameMale());
            $employee->setLastname($this->faker->lastName());
            $employee->setUsername('employe'.$i);                       

            $employee->setEmail($employee->getFirstname().'.'.$employee->getLastname().'@test.fr');


            $hash = $this->encoder->encodePassword($employee, $employee->getUsername());
            $employee->setPassword($hash)
                 ->setCreatedAt(new \DateTime())
                 ->setUpdatedAt(new \DateTime())
                 ->setCreatedUser(1)
                 ->setUpdatedUser(1);

            $this->addReference("employe ".$i, $employee);

            $this->manager->persist($employee);
        } 
        

        $employee = new Employee();

        $employee->setUsername('admin');
        
        $hash = $this->encoder->encodePassword($employee, $employee->getUsername());

        $employee
             ->setLastname('BOSS')
             ->setFirstname('Admin')
             ->setEmail('admin@test.fr')
             ->setPassword($hash)
             ->setRoles(EMPLOYEE::ROLE_ADMIN)
             ->setCreatedAt(new \DateTime())
             ->setUpdatedAt(new \DateTime())
             ->setCreatedUser(1)
             ->setUpdatedUser(1);

        $this->manager->persist($employee);

        
        $employee = new Employee();

        $employee->setUsername('manager');
        
        $hash = $this->encoder->encodePassword($employee, $employee->getUsername());

        $employee
             ->setLastname('MANAGER')
             ->setFirstname('Manager')  
             ->setEmail('manager@test.fr')
             ->setPassword($hash)
             ->setRoles(EMPLOYEE::ROLE_MANAGER)
             ->setCreatedAt(new \DateTime())
             ->setUpdatedAt(new \DateTime())
             ->setCreatedUser(1)
             ->setUpdatedUser(1);

        $this->manager->persist($employee);

        $this->manager->flush();

    }
}
