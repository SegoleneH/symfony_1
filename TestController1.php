<?php

namespace App\Controller;

use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/tag', name: 'app_test_tag')]
    public function tag(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $studentRepository = $em->getRepository(Student::class);
        $tagRepository = $em->getRepository(Tag::class);

        // Création d'un nouveau tag
        $foo = new Tag();
        $foo->setName('Foo');
        $foo->setDescription('Foo bar baz');
        $em->persist($foo);

        try{
            $em->flush();
        } catch(Exception $e) {
            dump($e->getMessage());
        }
        //Récupération de l'objet "tag" dont l'id est 1.
        $tag = $tagRepository->find(1);
        
        //Récupération de l'objet "tag" dont l'id est 15.
        $tag15 = $tagRepository->find(15);
        if ($tag15) {
            $em->remove($tag15);
            $em->flush();
        }

        //Récupération de l'objet "tag" dont l'id est 4.
        $tag4 = $tagRepository->find(4);
        $tag4->setName('Python');
        $tag4->setDescription(null);
        $em->flush();

        // Récupération du student dont l'id est 1.
        $student =$studentRepository->find(1);
        
        //Association du tag 4 au student 1.
        $student->addTag($tag4);
        $em->flush();
        

        $cssTag = $tagRepository->findOneBy([
            'name' => 'CSS',
        ]);

        $nullDescriptionTags = $tagRepository->findBy([
            'name' => 'CSS',
        ], [
            'name' => 'ASC',
        ]);

        $nullDescriptionTags = $tagRepository->findByNullDescription();


        // Récupération de tous les tags avec une description.
        $notNullDescriptionTags = $tagRepository->findByNotNullDescription();

        // Récupération de la liste complète des tags.
        $tags = $tagRepository->findAll();


        // Récupération des tags contenant certains mots clés
        $keywordTags1 = $tagRepository->findByKeyword('HTML');
        $keywordTags2 = $tagRepository->findByKeyword('CSS');

        $schoolYearRepository = $em->getRepository(SchoolYear::class);
        $schoolYear = $schoolYearRepository->find(1);
        $schoolYearTags = $tagRepository->findBySchoolYear($schoolYear);

        $studentRepository = $em->getRepository(Student::class);
        $student = $studentRepository->find(2);
        $htmlTag = $tagRepository->find(1);
        $htmlTag -> addStudent($student);
        $em->flush();


        $title= 'Test des tags';

        return $this->render('test/tag.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,
            'tags' => $tags,
            'tag' => $tag,
            'cssTag' => $cssTag,
            'nullDescriptionTags' => $nullDescriptionTags,
            'notNullDescriptionTags' => $notNullDescriptionTags,
            'keywordTags1' => $keywordTags1,
            'keywordTags2' => $keywordTags2,
            'schoolYearTags' => $schoolYearTags,
            'htmlTag' => $htmlTag,
        ]);
    }

    #[Route('/school-year', name:'app_test_schoolyear')]
    public function schoolYear(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $schoolYearRepository = $em->getRepository(SchoolYear::class);

        // Création d'un nouveau tag
        $quuxSY = new SchoolYear();
        $quuxSY->setName('Quux');
        $quuxSY->setDescription('Quux school year');
        $startDate = new DateTime('2022-01-01'); // Utilisez des tirets (-) pour les dates
        $quuxSY->setStartDate($startDate);

        $endDate = new DateTime('2022-02-02'); // Utilisez des tirets (-) pour les dates
        $quuxSY->setEndDate($endDate);
        $em->persist($quuxSY);
        
        try {
            $em->flush();
        } catch (Exception $e) {
            dump($e->getMessage());
        }

        $schoolYear = $schoolYearRepository->find(1);
        $schoolYear->setName('Quux');
        $schoolYear->setDescription(null);
        // pas besoin d'appeler persist si l'objet provient de la BDD
        $em->flush();

        
        // Récupération de la liste complète des schoolyears.
        $schoolYears = $schoolYearRepository->findAll();

        $title2= 'Test SchoolYear';
        return $this->render('test/school-year.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title2,
            'schoolYears' => $schoolYears,
        ]);
    }
}