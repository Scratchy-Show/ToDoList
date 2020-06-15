<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var Generator
     */
    protected $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        $users = [];
        $tasks = [];

        // 10 Utilisateurs
        for ($u = 0; $u < 10; $u++) {
            $user = new User();

            $user
                ->setUsername($faker->userName)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setEmail($faker->unique()->safeEmail);

            $users[] = $user;

            $manager->persist($user);
        }

        // 10 Tâches
        for ($t = 0; $t < 10; $t++) {
            $task = new task();

            $task
                ->setCreatedAt($faker
                    ->dateTimeBetween($startDate = '-6 months', $endDate = 'now', $timezone = 'Europe/Paris'))
                ->setTitle($faker->word)
                ->setContent($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setIsDone($faker->boolean);

            $tasks[] = $task;

            $manager->persist($task);
        }
        $manager->flush();
    }
}
