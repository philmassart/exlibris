<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('genres', EntityType::class, [
                'required' => false,
                'label' => "Genres",
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (GenreRepository $genreRepository) {
                    return $genreRepository->myFindAllBuilder();
                }
            ])
            ->add('author_last', TextType::class, [
                'required' => false,
                'label' => "Auteur",
                'attr' => [
                    'class' => 'myfield',
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'label' => "Titre",
                'attr' => [
                    'class' => 'myfield',
                    'placeholder' => 'Titre'
                ]
            ])
            //
//            ->add('minYear', IntegerType::class, [
//                'required' => false,
//                'label' => false,
//                'attr' => [
//                    'placeholder' => 'Année minimale'
//                ]
//            ])
//            ->add('maxYear', IntegerType::class, [
//                'required' => false,
//                'label' => false,
//                'attr' => [
//                    'placeholder' => 'Année maximale'
//                ]
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BookSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
