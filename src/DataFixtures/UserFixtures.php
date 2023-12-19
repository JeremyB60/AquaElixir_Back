<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;
use Faker;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@admin.fr');
        $admin->setLastName('Admin');
        $admin->setFirstName('Admin');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, '123123123')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAccountStatus('active');
        $admin->setIsEmailConfirmed('1');
        $admin->setCreatedAt(new \DateTime()); // Utilisation de DateTime
        $admin->setConfirmationToken('');
        $manager->persist($admin);

        $user = new User();
        $this->addReference('user0', $user);
        $user->setEmail('jeremy.ballin@orange.fr');
        $user->setLastName('Ballin');
        $user->setFirstName('Jérémy');
        $user->setPassword(
            $this->passwordEncoder->hashPassword($admin, '123123123')
        );
        $user->setRoles(['ROLE_USER']);
        $user->setAccountStatus('active');
        $user->setIsEmailConfirmed('1');
        // Générer une date aléatoire sur une période de 3 mois
        $randomDays = mt_rand(0, 90); // Nombre de jours aléatoire entre 0 et 90
        $randomDate = new \DateTime("-$randomDays days");
        $user->setCreatedAt($randomDate); 
        $user->setConfirmationToken('');
        $manager->persist($user);

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $this->addReference('user' . $i, $user);
            $user->setEmail($faker->email);
            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, '123123123')
            );
            $user->setRoles(['ROLE_USER']);
            $user->setAccountStatus('active');
            $user->setIsEmailConfirmed('1');
            $user->setCreatedAt(new \DateTime()); // Utilisation de DateTime
            $user->setConfirmationToken('');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
