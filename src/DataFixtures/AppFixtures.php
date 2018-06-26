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
foreach ($this->getUserData() as [$nom, $prenom, $sexe, $age, $mdp, $mail, $username, $roles]) {
$user = new User();
$user->setNom($nom);
$user->setPrenom($prenom);
$user->setSexe($sexe);
$user->setAge($age);
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
// $userData = [nom, $prenom, $sexe, $age, $mdp, $mail, $username, $roles];
['Jane', 'Doe', 'F', '18', 'kitten', 'jane_admin@symfony.com', 'jane_admin', ['ROLE_ADMIN']],
    ['Brad', 'titi', 'H', '20', 'password', 'brad_titi@symfony.com', 'bradLeBG', ['ROLE_USER']],
];
}


}