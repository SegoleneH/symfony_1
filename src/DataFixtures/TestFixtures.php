<?php

namespace App\DataFixtures;

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
    }
}