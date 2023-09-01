<?php
namespace App\DataFixtures;

use DateTime;
use App\Entity\Tag;
use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
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
        return ['test'];
        //* charge les fixtures dans 'test'
    }


    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadTags();
        $this->loadProjects();
        $this->loadSchoolYears();
        $this->loadStudents();
        $this->loadUsers();

    }
    //* PROJECT
    public function loadProjects(): void
    {
        //données statiques
        $repository = $this->manager->getRepository(Tag::class);
        $tags = $repository->findAll();



        //shortlist pour données dynamiques
        $shortList = $this->faker->randomElements($tags, 3);

        // récupération d'un tag à partir de son id
        $htmlTag = $repository->find(1);
        $cssTag = $repository->find(2);
        $jsTag = $repository->find(3);


        // éléments à réutiliser dans les boucles
        $html = $tags[0];
        $html->getName();

        $tags[0]->getName();


        //* données statiques
        $datas = [
            [
                'name' => 'Site Vitrine',
                'description' => null,
                'clientName' => 'John Pemberton',
                'startDate' => new DateTime('2022-01-01'),
                'checkpointDate' => new DateTime('2022-04-01'),
                'deliveryDate' => new DateTime('2022-07-31'),
                'tags' => [$htmlTag, $cssTag],
            ],
            [
                'name' => 'Wordpress',
                'description' => null,
                'clientName' => 'Earl Grey',
                'startDate' => new DateTime('2022-03-11'),
                'checkpointDate' => new DateTime('2022-08-11'),
                'deliveryDate' => new DateTime('2022-11-11'),
                'tags' => [$cssTag, $jsTag],
            ],
            [
                'name' => 'API Rest',
                'description' => null,
                'clientName' => 'Minos Tenzin',
                'startDate' => new DateTime('2022-05-21'),
                'checkpointDate' => new DateTime('2022-09-01'),
                'deliveryDate' => new DateTime('2022-12-31'),
                'tags' => [$jsTag],
            ],
        ];

        foreach ($datas as $data) {
            $project = new Project();
            $project->setName($data['name']);
            $project->setDescription($data['description']);
            $project->setClientName($data['clientName']);
            $project->setStartDate($data['startDate']);
            $project->setCheckpointDate($data['checkpointDate']);
            $project->setDeliveryDate($data['deliveryDate']);

            foreach ($data['tags'] as $tag) {
                $project->addTag($tag);
            }

            $this->manager->persist($project);
        }
        $this->manager->flush();


        // données dynamiques
        for ($i = 0; $i < 30; $i++) {
            $project = new Project();
            $words = random_int(3, 5);
            $project->setName($this->faker->sentence($words));

            $words = random_int(8, 15);
            $project->setDescription($this->faker->optional(0.7)->sentence($words));

            $project->setClientName($this->faker->name());

            $startDate = $this->faker->dateTimeBetween('-1 year', '-10 months');
            $project->setStartDate($startDate);

            $checkpointDate = $this->faker->dateTimeBetween('-9 months', '-4 months');
            $project->setCheckpointDate($checkpointDate);

            $deliveryDate = $this->faker->dateTimeBetween('-2 months', 'now');
            $project->setDeliveryDate($deliveryDate);

            $tagsCount = random_int(1, 4);
            $shortList = $this->faker->randomElements($tags, $tagsCount);

            foreach ($shortList as $tag) {
                $project->addTag($tag);
            }

            $this->manager->persist($project);
        }

        $this->manager->flush();
    }

    //* SCHOOL YEARS
    // PROMOS À CRÉER   
    // Alan Turing
    // John Von Neuman
    // Brendan Eich
    public function loadSchoolYears(): void
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
        foreach ($datas as $data) {
            $schoolYear = new schoolYear();
            $schoolYear->setName($data['name']);
            $schoolYear->setDescription($data['description']);

            $schoolYear->setStartDate($data['startDate']);

            $schoolYear->setEndDate($data['endDate']);

            $this->manager->persist($schoolYear);
            // indique que la variable 'user' doit être stockée dans la base de données 
        }


        //* données dynamiques
        for ($i = 0; $i < 10; $i++) {
            $schoolYear = new SchoolYear();

            $words = random_int(2, 4);
            $schoolYear->setName($this->faker->unique()->sentence($words));
            $words = random_int(8, 15);
            $schoolYear->setDescription($this->faker->optional($weight = 0.7)->sentence($words));

            $startDate = $this->faker->dateTimeBetween('-1 year', '-6 months');
            $schoolYear->setStartDate($startDate);
            $endDate = $this->faker->dateTimeBetween('-6 months', 'now');
            $schoolYear->setEndDate($endDate);

            $this->manager->persist($schoolYear);
        }

        $this->manager->flush();
    }

    //* STUDENT
    public function loadStudents(): void
    {
        $userRepository = $this->manager->getRepository(User::class);
        $users = $userRepository->findAll();
        $foo = $userRepository->find(1);
        $bar = $userRepository->find(2);
        $baz = $userRepository->find(3);

        $schoolYearRepository = $this->manager->getRepository(SchoolYear::class);
        $schoolYears = $schoolYearRepository->findAll();
        $ga = $schoolYearRepository->find(1);
        $bu = $schoolYearRepository->find(2);
        $zo = $schoolYearRepository->find(3);

        $projectRepository =$this->manager->getRepository(Project::class);
        $projects = $projectRepository->findAll();
        $siteVitrine = $projectRepository->find(1);
        $wordpress = $projectRepository->find(2);
        $apiRest = $projectRepository->find(3);

        $tagRepository =$this->manager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();
        $html = $tagRepository->find(1);
        $css = $tagRepository->find(2);
        $js= $tagRepository->find(3);

        $datas = [
            [
                'user' => $foo,
                'schoolYear' => $ga,
                'firstName' => 'Walter',
                'lastName' => 'White',
                'projects' => [$siteVitrine],
                'tags' => [$html],
            ],
            [
                'user' => $bar,
                'schoolYear' => $bu,
                'firstName' => 'Jessie',
                'lastName' => 'Pinkman',
                'projects' => [$wordpress],
                'tags' => [$css],
            ],
            [
                'user' => $baz,
                'schoolYear' => $zo,
                'firstName' => 'Gustavo',
                'lastName' => 'Fring',
                'projects' => [$apiRest],
                'tags' => [$js],
            ],
        ];

             //* Boucle pour les données statiques
        foreach ($datas as $data) {
            $student = new Student();
            $student->setSchoolYear($data['schoolYear']);
            $student->setFirstName($data['firstName']);
            $student->setlastName($data['lastName']);

            // $data['projects'] = liste de projects
            $project = $data['projects'][0];
                // je récupère le 0ème élément
            $student->addProject($project);
                // je l'ajoute à student_project


            $tag = $data['tags'][0];
            $student->addTag($tag);
            
            $this->manager->persist($student);
        }
        $this->manager->flush();


        //     //* données dynamiques
        for ($i = 0; $i < 20; $i++) {
            $student = new Student();

            $student->setSchoolYear($this->faker->randomElement($schoolYears));

            $student->setFirstName($this->faker->firstName());

            $student->setLastName($this->faker->lastName());

            $project = $this->faker->randomElement($projects);
            $student->addProject($project);

            $tag = $this->faker->randomElement($tags);
            $student->addTag($tag);

            $this->manager->persist($student);
        }

        $this->manager->flush();
    }

    //* TAGS
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

        foreach ($datas as $data) {
            $tag = new Tag();
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            $this->manager->persist($tag);
        }

        //* données dynamiques
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();
            $words = random_int(1, 3);
            $tag->setName($this->faker->sentence($words));
            $words = random_int(8, 15);
            $tag->setDescription($this->faker->sentence($words));

            $this->manager->persist($tag);
        }

        $this->manager->flush();
    }

    //* USERS
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

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            //* hachage du mot de passe  ^fonction 
            $user->setPassword($password);
            $user->setRoles($data['roles']);

            $this->manager->persist($user);
            // indique que la variable 'user' doit être stockée dans la base de données 
        }


        //* données dynamiques
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password = $this->hasher->hashPassword($user, '123');
            //* hachage du mot de passe  ^fonction 
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $this->manager->persist($user);
        }

        $this->manager->flush();
    }

}