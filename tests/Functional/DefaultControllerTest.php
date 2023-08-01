<?php

namespace App\Tests\Functional;

use App\Tests\Functional\Traits\AuthentificationTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    use AuthentificationTestTrait;
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testHomepageNotFound(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    /**
     * @throws \Exception
     */
    public function testHomepage()
    {
        $this->loginUser();
        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

    /**
     * @throws \Exception
     */
    public function testHomepageAdmin()
    {
        $this->loginAdmin();
        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
        $this->assertSelectorTextContains('.btn.btn-primary', 'Créer un utilisateur');
    }
}
