<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$nom, $mdp, $mail, $username, $roles]) {
            $user = new User();
            $user->setNom($nom);
            $user->setMdp($this->passwordEncoder->encodePassword($user, $mdp));
            $user->setMail($mail);
            $user->setUsername($username);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
// $userData = [nom, $mdp, $mail, $username, $roles];
            ['Clerbout', 'password', 'sclerbout@symfony.com', 'Simon_admin', ['ROLE_ADMIN']],
            ['Pruvost', 'password', 'qpruvost@symfony.com', 'Quentin_admin', ['ROLE_ADMIN']],
            ['Lecoeuche', 'password', 'alecoeuche@symfony.com', 'Axel_admin', ['ROLE_ADMIN']],
        ];
    }


}