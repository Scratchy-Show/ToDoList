<?php

namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase // Permet de créer des tests avec des requêtes et des réponses
{
    use FixturesTrait;
    use NeedLogin;

    private $client = null;

    public function setUp()
    {
        // Récupère un client
        $this->client = self::createClient();
    }

    // Test l'affichage de la page /login
    public function testLoginActionDisplay()
    {
        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/login');

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');

        // Attend aucun selecteur particulier
        self::assertSelectorNotExists('.alert.alert-danger');
    }

    // Test la connexion avec un mauvais login
    public function testLoginActionWithBadCredentials()
    {
        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/login');

        // Récupère le bouton et le formulaire associé
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Test',
            '_password' => 'fakepassword'
        ]);

        // Soumet le formulaire
        $this->client->submit($form);

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Attend un selecteur particulier
        self::assertSelectorExists('.alert.alert-danger');

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }

    // Test la connexion avec un bon login
    public function testLoginActionWithGoodCredentials()
    {
        // Charge un fichier avec des données
        $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Arrête le noyau avant de créer le client
        self::ensureKernelShutdown();

        $this->client->request('POST', '/login_check', [
            '_username' => 'Test',
            '_password' => 'password'
        ]);

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        self::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence du H1
        self::assertSame(1, $crawler->filter('h1')->count());

        // Vérifie le contenue du titre
        self::assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
    }

    // Test le lien de déconnexion
    public function testLogoutLink()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui renvoie un crawler qui permet d'analyser le contenu de la page et stock la réponse en mémoire
        $crawler = $this->client->request('GET', '/');

        // Récupère le lien
        $link = $crawler->selectLink('Se déconnecter')->link();

        // Click sur le lien
        $this->client->click($link);

        // Suit la redirection et charge la page suivante
        $this->client->followRedirect();

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

    // Test le chemin de déconnexion
    public function testLogoutPath()
    {
        // Charge un fichier avec des données
        $users = $this->loadFixtureFiles([dirname(__DIR__) . '/DataFixtures/AppFixtures.yaml']);

        // Connecte l'utilisateur au client
        $this->login($this->client, $users['user']);

        // Requête qui analyse le contenu de la page
        $this->client->request('GET', '/logout');

        // Suit la redirection et charge la page suivante
        $this->client->followRedirect();

        // Statut de la réponse attendu : type 302
        self::assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());

        // Suit la redirection et charge la page suivante
        $crawler = $this->client->followRedirect();

        // Statut de la réponse attendu : type 200
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Vérifie la présence des champs d'identification
        self::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        self::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        // Vérifie la présence du bouton "Se connecter"
        self::assertSelectorTextContains('button', 'Se connecter');
    }
}