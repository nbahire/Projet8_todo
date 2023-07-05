<?php

namespace App\Tests\Controller;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private UserRepository $userRepository;

    public function setUp() : void

    {
        $this->client = static::createClient();

        $this->urlGenerator = $this->client->getContainer()->get('router.default');

    }
    public function testLogin()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_login'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }
}
