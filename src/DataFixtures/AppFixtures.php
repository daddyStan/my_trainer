<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUserName('stan');

        $password = $this->encoder->encodePassword($user, 'qweqwe');
        $user->setPassword($password);

        $user->setEmail('sss@mail.com');
        $user->setIsActive(true);
        $user->setLastVisitDate(new \DateTime('2018-01-02'));
        $user->setRegistrationDate(new \DateTime('2018-01-01'));

        $manager->persist($user);
        $manager->flush();
    }
}
