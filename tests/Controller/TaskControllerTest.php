<?php


namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase // Permet de créer des tests avec des requêtes et des réponses
{
    use FixturesTrait;
    use NeedLogin;

    private $client = null;
    private $getEntityManager = null;

    public function setUp()
    {
        // Récupère un client
        $this->client = self::createClient();

        $this->getEntityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    // Test l'accès à la page de la liste des tâches si non identifié
    public function testListActionNotLogged()
    {
        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/tasks');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test le lien pour afficher la page de la liste des tâches si identifié
    public function testListActionLink()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/');

        // Récupère le lien
        $link = $crawler->selectLink('Consulter la liste des tâches à faire')->link();

        // Click sur le lien
        $this->client->click($link);

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains(
            'h1',
            'Liste des tâches à faire'
        );
    }

    // Test le chemin pour afficher la page de la liste des tâches si identifié
    public function testListActionPath()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du titre
        self::assertSame(1, $crawler->filter('html:contains("Liste des tâches à faire")')->count());
    }

    // Test l'accès à la page de la liste des tâches terminées si non identifié
    public function testListFinishActionNotLogged()
    {
        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/tasks/finish');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test le lien pour afficher la page de la liste des tâches terminées si identifié
    public function testListFinishActionLink()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/');

        // Récupère le lien
        $link = $crawler->selectLink('Consulter la liste des tâches terminées')->link();

        // Click sur le lien
        $this->client->click($link);

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains(
            'h1',
            'Liste des tâches terminées'
        );
    }

    // Test le chemin pour afficher la page de la liste des tâches terminées si identifié
    public function testListFinishActionPath()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/finish');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du titre
        self::assertSame(1, $crawler->filter('html:contains("Liste des tâches terminées")')->count());
    }

    // Test l'accès à la page création d'une tâche si non identifié
    public function testCreateActionNotLogged()
    {
        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/tasks/create');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test le lien pour afficher la page de création d'une tâche si identifié
    public function testCreateActionLink()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks');

        // Récupère le lien
        $link = $crawler->selectLink('Créer une tâche')->link();

        // Click sur le lien
        $this->client->click($link);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/create');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains(
            'h1',
            'Créer une tâche'
        );

        // Vérifie la présence des champs de création
        self::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        self::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());
    }

    // Test la création d'une tâche si identifié
    public function testCreateAction()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test création d\'une tâche',
            'task[content]' => 'Contenu de la tâche test'
        ]);

        // Soumet le formulaire
        $this->client->submit($form);

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Attend un selecteur particulier
        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());

        // Vérifie le contenu du message flash
        self::assertGreaterThan(
            1,
            $crawler->filter('div:contains("La tâche a été bien été ajoutée.")')->count()
        );

        // Vérifie que la nouvelle tâche est bien affichée
        self::assertSame(1, $crawler->filter('html:contains("Test création d\'une tâche")')->count());
        self::assertSame(1, $crawler->filter('html:contains("Contenu de la tâche test")')->count());

        // Vérifie le pseudo du l'utilisateur
        $userTaskCreated = $this->getEntityManager->getRepository(Task::class)->findOneBy([
            'title' => 'Test création d\'une tâche'
        ]);
        $this->assertSame('Test User', $userTaskCreated->getUser()->getUsername());
    }

    // Test la création d'une tâche - Si aucune données
    public function testCreateActionEmptyData()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => '',
            'task[content]' => ''
        ]);

        // Soumet le formulaire
        $crawler = $this->client->submit($form);

        // Vérifie la présence et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir un titre.")')->count()
        );

        // Vérifie la présence et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir du contenu.")')->count()
        );
    }

    // Test la création d'une tâche - Si il manque le titre
    public function testCreateActionEmptyTitle()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => '',
            'task[content]' => 'Contenu de la tâche test'
        ]);

        // Soumet le formulaire
        $crawler = $this->client->submit($form);

        // Vérifie la présence et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir un titre.")')->count()
        );

        // Vérifie l'absence du message
        self::assertSame(
            0,
            $crawler->filter('html:contains("Vous devez saisir du contenu.")')->count()
        );
    }

    // Test la création d'une tâche - Si il manque le contenu
    public function testCreateActionEmptyContent()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test création d\'une tâche',
            'task[content]' => ''
        ]);

        // Soumet le formulaire
        $crawler = $this->client->submit($form);

        // Vérifie l'absence du message
        self::assertSame(
            0,
            $crawler->filter('html:contains("Vous devez saisir un titre.")')->count()
        );

        // Vérifie la présence et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir du contenu.")')->count()
        );
    }

    // Test le lien pour retourner à la page de la liste des tâches
    public function testCreateActionReturnButton()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui analyse le contenu de la page
        $crawler = $this->client->request('GET', '/tasks/create');

        // Récupère le lien
        $link = $crawler->selectLink('Retour à la liste des tâches')->link();

        // Click sur le lien
        $this->client->click($link);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains(
            'h1',
            'Liste des tâches à faire'
        );
    }

    // Test l'accès à la page d'édition d'une tâche si non identifié
    public function testEditActionNotLogged()
    {
        // Charge un fichier avec des données
        $tasks = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Récupère la tâche dans la base de données de test
        $task =  $tasks['task1'];

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/'. $task->getId() .'/edit');

        // Statut de la réponse attendu : type 302
        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test la modification d'une tâche si identifié
    public function testEditAction()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/tasks/2/edit');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains('h1', 'Modifier la tâche');

        // Vérifie la présence des champs de modification
        self::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        self::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Modification d\'une tâche',
            'task[content]' => 'Modifier le contenu de la tâche'
        ]);

        // Soumet le formulaire
        $this->client->submit($form);

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains('h1', 'Liste des tâches à faire');

        // Attend un selecteur particulier
        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());

        // Vérifie le contenu du message flash
        self::assertGreaterThan(
            1,
            $crawler->filter('div:contains("La tâche a bien été modifiée.")')->count()
        );

        // Vérifie que la nouvelle tâche est bien affichée
        self::assertSame(
            1,
            $crawler->filter('html:contains("Modification d\'une tâche")')->count()
        );
        self::assertSame(
            1,
            $crawler->filter('html:contains("Modifier le contenu de la tâche")')->count()
        );
    }

    // Test l'accès au basculement d'une tâche si non identifié
    public function testToggleTaskActionNotLogged()
    {
        // Charge un fichier avec des données
        $tasks = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Récupère la tâche dans la base de données de test
        $task =  $tasks['task1'];

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/'. $task->getId() .'/toggle');

        // Statut de la réponse attendu : type 302
        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test la basculement d'une tâche de true en false
    public function testToggleInProgress()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/1/toggle');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du contenu du message flash
        self::assertSame(1, $crawler->filter('div.alert-success:contains("est en cours.")')->count());
    }

    // Test la basculement d'une tâche de false en true
    public function testToggleCompleted()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/2/toggle');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du contenu du message flash
        self::assertSame(1, $crawler->filter('div.alert-success:contains("est terminée.")')->count());
    }

    // Test l'accès à la suppression d'une tâche si non identifié
    public function testDeleteTaskActionNotLogged()
    {
        // Charge un fichier avec des données
        $tasks = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Récupère la tâche dans la base de données de test
        $task =  $tasks['task1'];

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/'. $task->getId() .'/delete');

        // Statut de la réponse attendu : type 302
        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test la suppression d'une tâche si l'utilisateur est l'auteur
    public function testDeleteUserHisTaskAction()
    {
        // Charge un fichier avec des données
        $data = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $data['user']);

        // Récupère la tâche dans la base de données de test
        $task =  $data['task1'];

        $task->setUser($data['user']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/1/delete');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(
            1,
            $crawler->filter('div.alert-success:contains("La tâche a bien été supprimée.")')->count()
        );
    }

    // Test la suppression d'une tâche si l'utilisateur n'est est pas l'auteur
    public function testDeleteUserNotHisTaskAction()
    {
        // Charge un fichier avec des données
        $data = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $data['user']);

        // Récupère la tâche dans la base de données de test
        $task =  $data['task1'];

        $task->setUser($data['admin']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/1/delete');

        // Statut de la réponse attendu : type 302
        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains('h1', 'Liste des tâches à faire');

        // Attend un selecteur particulier
        self::assertSame(1, $crawler->filter('.alert.alert-danger')->count());

        // Vérifie le contenu du message flash
        self::assertGreaterThan(
            1,
            $crawler->filter('div:contains("Seul l\'auteur de la tâche peut la supprimer")')->count()
        );
    }

    // Test la suppression d'une tâche anonyme si l'utilisateur est un USER
    public function testDeleteUserAnonymousTaskAction()
    {
        // Charge un fichier avec des données
        $data = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $data['user']);

        // Récupère la tâche dans la base de données de test
        $task =  $data['task1'];

        $task->setUser($data['anonyme']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/1/delete');

        // Statut de la réponse attendu : type 302
        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains('h1', 'Liste des tâches à faire');

        // Attend un selecteur particulier
        self::assertSame(1, $crawler->filter('.alert.alert-danger')->count());

        // Vérifie le contenu du message flash
        self::assertGreaterThan(
            1,
            $crawler->filter('div:contains("Seul un admin peut supprimer une tâche de l\'utilisateur anonyme")')
                ->count()
        );
    }

    // Test la suppression d'une tâche anonyme si l'utilisateur est un ADMIN
    public function testDeleteAdminAnonymousTaskAction()
    {
        // Charge un fichier avec des données
        $data = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $data['admin']);

        // Récupère la tâche dans la base de données de test
        $task =  $data['task1'];

        $task->setUser($data['anonyme']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', 'tasks/1/delete');

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(
            1,
            $crawler->filter('div.alert-success:contains("La tâche a bien été supprimée.")')->count()
        );
    }
}
