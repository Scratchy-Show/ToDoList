<?php

namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase // Permet de créer des tests avec des requêtes et des réponses
{
    use FixturesTrait;
    use NeedLogin;

    private $client = null;

    public function setUp()
    {
        // Récupère un client
        $this->client = self::createClient();
    }

    // Test l'accès à la page d'accueil si non identifié
    public function testIndexActionNotLogged()
    {
        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/');

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
        $this->assertSelectorTextContains('button', 'Se connecter');
    }

    // Test l'accès à la page d'accueil si identifié
    public function testIndexActionLoggedInAsUser()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie le contenue du titre
        self::assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
    }
}
