<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Genre;
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
            ->add('title')
            ->add('year')
            ->add('author_last')
            ->add('author_first')
            ->add('publisher')
            ->add('description')
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
            ->add('imageFile', FileType::class,[
                'required' => false
            ])
            ->add('city')
            ->add('collection')
            ->add('volume')
            ->add('storage')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'translation_domain' => 'forms'
        ]);
    }
}
