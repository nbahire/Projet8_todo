<?php

namespace App\Tests\Functional;

use App\Tests\Functional\Traits\AuthentificationTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use AuthentificationTestTrait;
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws \Exception
     */
    public function testLogin()
    {
        $this->loginUser();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testRedirectToHomepageWhenLoggedIn()
    {
        $this->loginUser();

        $this->client->request('GET', '/login');
        $this->assertResponseRedirects('/');
    }

    /**
     * @throws \Exception
     */
    public function testLogout()
    {
        $this->loginUser();

        $this->client->request('GET', '/logout');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.login-form', "Se connecter");

        // Check if user is logged out
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.login-form', "Se connecter");
    }
}
