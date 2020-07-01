<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 * @codeCoverageIgnore
 */
class UpdateTaskAnonymousCommand extends Command
{
    // Expose l'EntityManager au niveau de la classe
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // Met à jour la valeur de la variable $entityManager par injection
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // le nom de la commande après "bin/console"
            ->setName('todolist:updatetaskanonymous')
            // La courte description affichée lors de l'exécution de "php bin/console list"
            ->setDescription('Tasks without users are linked to an Anonymous user')
            // La description complète de la commande affichée lors de l'exécution de la commande avec
            // l'option "--help"
            ->setHelp('Tasks without users are linked to an Anonymous user')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->entityManager;

        // Accès aux référentiels
        $repoUser = $em->getRepository("App:User");
        $repoTask = $em->getRepository("App:Task");

        // Récupérations des entités
        $user = $repoUser->findBy(['username' => 'Anonyme']);
        $tasks = $repoTask->findBy(['user' => null]);

        foreach ($tasks as $task) {
            $task->setUser($user[0]);
            $em->persist($task);
            $em->flush();
        }
        $output->writeln('The tasks without users are updated with the user Anonyme.');
        return 0;
    }
}