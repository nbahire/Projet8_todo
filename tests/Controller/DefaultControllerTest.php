<?php

namespace App\Tests\Controller;
use App\Repository\UserRepository;
use App\Tests\Controller\Traits\AuthentificationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    use AuthentificationTrait;
    private KernelBrowser $client;

    public function setUp() : void
    {
        $this->client = static::createClient();
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
}
