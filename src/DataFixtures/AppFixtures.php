<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
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
        return ['prod', 'test'];
        //* charge les fixtures dans 'prod' ou dans 'test'
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadAdmins();
    }

    public function loadAdmins(): void
    {
        $datas = [
            [
                'email' => 'admin@example.com',
                'password' => '123',
                'roles' => ['ROLE_ADMIN'],
            ],
        ];

        foreach ($datas as $data){
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            //* hachage du mot de passe  ^fonction 
            $user->setPassword($password);
            $user->setRoles($data['roles']);

            $this->manager->persist($user);
            // indique que la variable 'user' doit être stockée dans la base de données 
        }
        
        $this->manager->flush();
    }
}