<?php

namespace App\DataFixtures;

use App\Controller\Admin\AdminGenreController;
use App\Entity\Genre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $genres = [
            'Art musique et cinéma',
            'Bandes dessinées',
            'Cuisine',
            'Développement personnel',
            'Dictionnaires & langues',
            'Droit & économie',
            'Essais et documents',
            'Guides pratiques',
            'Histoire',
            'Humour',
            'Informatique et internet',
            'Jeunesse',
            'Littérature',
            'Littérature sentimentale',
            'Policier, suspense, thrillers',
            'Religion et spiritualité',
            'Sciences sociales',
            'Sciences, techniques & médecine',
            'Scolaire',
            'SF, Fantasy',
            'Sport loisirs et vie pratique',
            'Théâtre',
            'Tourisme et voyages'
        ];

        foreach($genres as $g)
        {
            $genre = new Genre();
            $genre->setName($g);
            $manager->persist($genre);
        }

        $manager->flush();
    }
}
