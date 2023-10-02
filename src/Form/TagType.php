<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('projects', EntityType::class, [
                'class' => Project::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC')
                        ->addOrderBy('p.id', 'ASC');
                },
                'choice_label' => function(Project $project) {
                    return "{$project->getName()} ({$project->getId()})";
                    //* FONCTION MAGIQUE POUR RESOUDRE L'ERREUR "can't convert to string"
                    //* The function takes a Project object as an argument and returns a string that concatenates the project's name and ID. 
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ], 
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
