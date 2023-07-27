<?php

namespace App\Tests\Controller;
use App\Repository\UserRepository;
use App\Tests\Controller\Traits\AuthentificationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
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
    public function testLogin()
    {
        $this->loginUser();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
