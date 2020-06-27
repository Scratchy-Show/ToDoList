<?php


namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase // Permet de créer des tests avec des requêtes et des réponses
{
    use FixturesTrait;
    use NeedLogin;

    private $client = null;

    public function setUp()
    {
        // Récupère un client
        $this->client = self::createClient();
    }

    // Test le chemin pour afficher la page de la liste des tâches
    public function testListActionPath()
    {
        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/users');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    // Test le chemin pour accéder à la page créer un utilisateur
    public function testCreateActionPath()
    {
        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du titre
        self::assertSame(1, $crawler->filter('html:contains("Créer un utilisateur")')->count());
    }

    // Test le lien pour accéder à la page créer un utilisateur
    public function testCreateActionLink()
    {
        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users');

        // Récupère le lien
        $link = $crawler->selectLink('Créer un utilisateur')->link();

        // Click sur le lien
        $this->client->click($link);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/create');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains('h1', 'Créer un utilisateur');

        // Vérifie la présence des champs de création
        self::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        self::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
        self::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());
        self::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
    }

    // Test la création d'un utilisateur
    public function testCreateAction()
    {
        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Test utilisateur',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user@email.com',
            'user[role]' => 'ROLE_USER'
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
            $crawler->filter('div:contains("L\'utilisateur a bien été ajouté.")')->count()
        );

        // Vérifie que la nouvelle tâche est bien affichée
        self::assertSame(1, $crawler->filter('html:contains("Test utilisateur")')->count());
        self::assertSame(1, $crawler->filter('html:contains("user@email.com")')->count());
    }

    // Test la création d'un utilisateur - Si aucune données
    public function testCreateActionEmptyData()
    {
        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => '',
            'user[password][first]' => '',
            'user[password][second]' => '',
            'user[email]' => '',
        ]);

        // Soumet le formulaire
        $crawler = $this->client->submit($form);

        // Vérifie la présence de username et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir un nom d\'utilisateur.")')->count()
        );

        // Vérifie la présence de password et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir un nom mot de passe.")')->count()
        );

        // Vérifie la présence de email et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Vous devez saisir une adresse email.")')->count()
        );
    }

    // Test la création d'un utilisateur - Si les mots de passe sont différents
    public function testCreateActionDifferentPasswords()
    {
        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/create');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Test utilisateur',
            'user[password][first]' => '1password1',
            'user[password][second]' => '2password2',
            'user[email]' => 'user@email.com',
        ]);

        // Soumet le formulaire
        $crawler = $this->client->submit($form);

        // Vérifie l'égalité de password et le contenu du message
        self::assertSame(
            1,
            $crawler->filter('html:contains("Les deux mots de passe doivent correspondre.")')->count()
        );
    }

    // Test le chemin pour accéder à la page d'édition d'un utilisateur
    public function testEditActionPath()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Récupère la tâche dans la base de données de test
        $user =  $users['user'];

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/'. $user->getId() .'/edit');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du titre
        self::assertSame(1, $crawler->filter('html:contains("Modifier")')->count());
    }

    // Test le lien pour accéder à la page d'édition d'un utilisateur
    public function testEditActionLink()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Récupère la tâche dans la base de données de test
        $user =  $users['user'];

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users');

        // Récupère le lien
        $link = $crawler->selectLink('Edit')->link();

        // Click sur le lien
        $this->client->click($link);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/'. $user->getId() .'/edit');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie la contenu du titre
        self::assertSelectorTextContains('h1', 'Modifier');

        // Vérifie la présence des champs de création
        self::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        self::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
        self::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());
        self::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
    }

    // Test l'édition d'un utilisateur
    public function testEditAction()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Récupère la tâche dans la base de données de test
        $user =  $users['user'];

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/users/'. $user->getId() .'/edit');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'Test édition utilisateur',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'useredit@email.com',
        ]);

        // Soumet le formulaire
        $this->client->submit($form);

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Attend un selecteur particulier
        self::assertSame(1, $crawler->filter('.alert.alert-success')->count());

        // Vérifie le contenu du message flash
        static::assertSelectorTextSame('div.alert', 'Superbe ! L\'utilisateur a bien été modifié');

        // Vérifie que la nouvelle tâche est bien affichée
        self::assertSame(1, $crawler->filter('html:contains("Test édition utilisateur")')->count());
        self::assertSame(1, $crawler->filter('html:contains("useredit@email.com")')->count());
    }
}