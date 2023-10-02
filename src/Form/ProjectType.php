<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('clientName')
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                //* ^ Ligne pour ajouter le calendrier interactif dans form
            ])
            ->add('checkpointDate', DateType::class, [
                'widget' => 'single_text',
                //* ^ Ligne pour ajouter le calendrier interactif dans form
                'required' => false,
            ])
            ->add('deliveryDate', DateType::class, [
                'widget' => 'single_text',
                //* ^ Ligne pour ajouter le calendrier interactif dans form
                'required' => false,
            ])
            ->add('students', EntityType::class, [
                'class' => Student::class,
                'choice_label' => function(Student $student) {
                    return "{$student->getFirstName()} (id {$student->getLastName()})";
                },
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.firstName', 'ASC')
                        ->addOrderBy('s.lastName', 'ASC');
                },
                'by_reference' => false,
                //^ définir by_reference sur true signifie que les modifications 
                //^ dans le champ du formulaire modifient immédiatement les données d'origine, 
                //^ en tandis que le définir sur false signifie que les modifications sont conservées
                //^  séparément jusqu'à ce que vous soumettiez le formulaire.
                //! @WARNING : à ne rajouter que pour les associations qui sont le côté possédant
                //! ex: nécessaire si dans l'entité Project, la propriété students possède l'attribut mappedBy
                //! dans ce cas, Student est possédant et Project est possédé (ou inverse) 
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                //* pas nécessaire de rajouter by_reference car pas le côté possédant
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
