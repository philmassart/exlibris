<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Genre;
use App\Repository\FeatureRepository;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => "form.title"
            ])
            ->add('year', null, [
                'label' => "form.year"
            ])
            ->add('author_last', null, [
                'label' => "form.authorlast"
            ])
            ->add('author_first', null, [
                'label' => "form.authorfirst"
            ])
            ->add('publisher', null, [
                'label' => "form.publisher"
            ])
            ->add('description')
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'label' => "form.genre",
                'multiple' => true,
                'required' => false,
                'query_builder' => fn(GenreRepository $genreRepository) => $genreRepository->myFindAllBuilder()
            ])
            ->add('imageFile', FileType::class,[
                'required' => false,
                'label' => "form.image file"
            ])
            ->add('city', null, [
                'label' => "form.city"
            ])
            ->add('collection')
            ->add('volume', null, [
                'label' => "form.volume"
            ])
            ->add('storage', null, [
                'label' => "form.storage"
            ])

            ->add('isbn', null, [
                'label' => "form.isbn"
            ])

            ->add('lendedto', null, [
                'label' => "form.lendedto"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
