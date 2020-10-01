<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public const USER_REFERENCE_1 = 'user1';
    public const USER_REFERENCE_2 = 'user2';

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('admin@quotemachine.com');
        $user1->setRoles((array) 'ROLE_ADMIN');
        $user1->setName('Administrateur');
        $password = $this->encoder->encodePassword($user1, 'admin');
        $user1->setPassword($password);
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('loic@quotemachine.com');
        $user2->setName('Loïc');
        $password = $this->encoder->encodePassword($user2, 'test');
        $user2->setPassword($password);
        $manager->persist($user2);

        $manager->flush();
        $this->addReference(self::USER_REFERENCE_1, $user1);
        $this->addReference(self::USER_REFERENCE_2, $user2);
    }
}
