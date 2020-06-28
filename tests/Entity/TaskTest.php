<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class TaskTest extends KernelTestCase // Permet de récupérer le validateur avec des logiques plus complexes
{
    use FixturesTrait;

    private $task;

    public function setUp()
    {
        $this->task = new Task();
    }

    public function testConstruct()
    {
        $this->assertFalse($this->task->isDone());
        $this->assertInstanceOf('DateTime', $this->task->getCreatedAt());
    }

    public function testId()
    {
        $this->assertSame(null, $this->task->getId());
    }

    public function testGetCreatedAt()
    {
        $this->task->setCreatedAt(new DateTime);
        $this->assertSame(date('Y-m-d H:i:s'), $this->task->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testTitle()
    {
        $this->task->setTitle('Test titre');
        $this->assertSame('Test titre', $this->task->getTitle());
    }

    public function testContent()
    {
        $this->task->setContent('Test contenu');
        $this->assertSame('Test contenu', $this->task->getContent());
    }

    public function testIsDone()
    {
        $this->assertSame(false, $this->task->IsDone());
    }

    public function testToggle()
    {
        $task = new Task;
        $task->toggle(true);
        $this->assertSame(true, $task->isDone());
    }

    public function testSetUserTask()
    {
        $user = new User;
        $user->setUsername('Test User');

        $task = new Task;
        $task->setUser($user);

        $this->assertSame('Test User', $task->getUser()->getUsername());
    }

    // Récupère l'entité
    public function getEntity(): Task
    {
        return (new Task())
            ->setCreatedAt(new Datetime())
            ->setTitle('Tâche 1 test')
            ->setContent('Contenu de test')
            ->setIsDone(false)
            ;
    }

    // Assertion personnalisée qui attend aucune erreur
    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();

        // Récupère le Validateur depuis le container pour valider l'entité et récupère une liste d'erreur
        $errors = self::$container->get('validator')->validate($task);

        // Sauvegarde l'ensemblde des messages dans un tableau
        $messages = [];

        // Chaque erreur est un objet ConstraintViolation, c'est à dire qu'il y a un problème de contrainte
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            // Pour chaque message: la clé du problème . => . message du problème
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        // Indique le nombre d'erreur, l'erreur et les messages d'erreurs
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    // Permet de vérifier qu'une entité valide reste valide
    public function testValidEntity()
    {
        // Initialise l'entité avec aucune erreur
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankTitleEntity()
    {
        // Titre vide
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testInvalidBlankContentEntity()
    {
        // Contenu vide
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }
}