<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends KernelTestCase // Permet de récupérer le validateur avec des logiques plus complexes
{
    use FixturesTrait;

    // Récupère l'entité
    public function getEntity(): User
    {
        return (new User())
            ->setUsername('Test1')
            ->setPassword('password')
            ->setEmail('nom1@exemple.fr')
            ;
    }

    // Assertion personnalisée qui attend aucune erreur
    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();

        // Récupère le Validateur depuis le container pour valider l'entité et récupère une liste d'erreur
        $errors = self::$container->get('validator')->validate($user);

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

    public function testInvalidUniqueEntity()
    {
        // Charge un fichier avec des données pour User
        $this->loadFixtureFiles([dirname(__DIR__) . '/Fixtures/User.yaml']);
        // Pseudo déjà existant
        $this->assertHasErrors($this->getEntity()->setUsername('Test'), 1);
        // Email déjà existant
        $this->assertHasErrors($this->getEntity()->setEmail('nom@exemple.fr'), 1);
    }

    public function testInvalidUsernameEntity()
    {
        // Pseudo trop long
        $this->assertHasErrors($this->getEntity()->setUsername('Tesssssssssssssssssssssssst'), 1);
    }

    public function testInvalidBlankUsernameEntity()
    {
        // Pseudo vide
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    public function testInvalidBlankEmailEntity()
    {
        // Email vide
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testInvalidEmailEntity()
    {
        // Email trop long
        $this->assertHasErrors($this->getEntity()
            ->setEmail('nom@exemmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmple.fr'), 1);
        // Email invalide
        $this->assertHasErrors($this->getEntity()->setEmail('nomexemple.fr'), 1);
    }
}