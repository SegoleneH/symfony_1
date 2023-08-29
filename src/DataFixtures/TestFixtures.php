<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    //* outil qui permet de générer str ou nombres aléatoires
    // simulation de compte utilisateur par exemple (remplace Mockaroo)

    private $hasher;
    //* transmis par symfony hache les mots de passe 

    private $manager;
    //* transmis par symfony, entity manager

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    //* permet d'indiquer à symfony de quel type de fixture il s'agit
    {
        return['test'];
        //* charge les fixtures dans 'test'
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this -> loadUsers();
        $this -> loadTags();
        $this -> loadSchoolYears();
    }


    public function loadTags(): void
    {
        //données statiques
        $datas = [
            [
                'name' => 'HTML',
                'description' => null,
            ],
            [
                'name' => 'CSS',
                'description' => null,
            ],
            [
                'name' => 'JS',
                'description' => null,
            ],
        ];

        foreach($datas as $data){
            $tag = new Tag();
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            $this->manager->persist($tag);
        }

        //* données dynamiques
        for($i=0; $i<10; $i++){
            $tag = new Tag();
            $words = random_int(1, 3);
            $tag->setName($this->faker->sentence($words));
            $words = random_int(8, 15);
            $tag->setDescription($this->faker->sentence($words));
            
            $this->manager->persist($tag);
        }

        $this->manager->flush();
    }


    // PROMOS À CRÉER   
        // Alan Turing
        // John Von Neuman
        // Brendan Eich
    public function loadSchoolYear(): void
    {
        $datas = [
            [
                'name' => 'Alan Turing',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'John Van Neuman',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'Brendan Eich',
                'description' => null,
                'startDate' => null,
                'endDate' => null,
            ],
        ];

        //* Boucle pour les données statiques
        foreach ($datas as $data)
        {
            $schoolYear = new Tag();
            $schoolYear->setName($data['name']);
            $schoolYear->setDescription($data['description']);

            $schoolYear->setStartDate($data['startDate']);
            
            $schoolYear->setEndDate($data['endDate']);
            
            $this->manager->persist($schoolYear);
            // indique que la variable 'user' doit être stockée dans la base de données 


            //& $startDate = $this->faker->dateTimeBetween('-1 year', '-6 months');
            //& $endDate = $this->faker->dateTimeBetween('-6 months', 'now');
        }
        
        
        //* données dynamiques
         for ($i = 0; $i < 10; $i++) {
            $schoolYear = new SchoolYear();
            
            $words = random_int(1, 3);
            $schoolYear->setName($this->faker->sentence($words));
            $words = random_int(8, 15);
            $schoolYear->setDescription($this->faker->sentence($words));

            $startDate = $this->faker->dateTimeBetween('-1 year', '-6 months');
            $schoolYear->setStartDate($startDate);
            $endDate = $this->faker->dateTimeBetween('-6 months', 'now');
            $schoolYear->setEndDate($endDate);

            $this->manager->persist($schoolYear);
        }
        
        $this->manager->flush();
    }


    public function loadUsers(): void
    {
        $datas = [
            [
                'email' => 'foo@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
            [
                'email' => 'bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
            [
                'email' => 'baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
        ];

        foreach ($datas as $data)
        {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            //* hachage du mot de passe  ^fonction 
            $user = setPassword($password);
            $user = setRoles($data['roles']);

            $this->manager->persist($user);
            // indique que la variable 'user' doit être stockée dans la base de données 
        }
        
        
        //* données dynamiques
        for($i=0; $i<100; $i++){
            $user = new User();
            $user->setEmail($this->faker->safeEmail());
            $password = $this->hasher->hashPassword($user, '123');
            //* hachage du mot de passe  ^fonction 
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            
            $this->manager->persist($user);
        }
        
        $this->manager->flush();
    }
}