<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('isbn', TextType::class, [
                'label' => 'Numéro ISBN'
            ])
            ->add('year', NumberType::class, [
                'label' => 'Année de publication (AAAA)'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du livre'
            ])
            ->add('author_id', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'firstname',
                'label' => 'Auteur',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')->orderBy('a.firstname', 'ASC');
                }
            ])
            ->add('publisher_id', EntityType::class, [
                'class' => Publisher::class,
                'choice_label' => 'name',
                'label' => 'Editeurs',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('e')->orderBy('e.name', 'ASC');
                }
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
