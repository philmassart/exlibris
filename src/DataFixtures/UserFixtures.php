<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordEncoderInterface $encoder)
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('demo2');
        $user->addRole('ROLE_ADMIN');
        $user->setPassword($this->encoder->encodePassword($user, 'demo2'));
        $manager->persist($user);

        $manager->flush();
    }
}
