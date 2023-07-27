<?php

namespace App\Tests\Controller;
use App\Repository\UserRepository;
use App\Tests\Controller\Traits\AuthentificationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    use AuthentificationTrait;
    private KernelBrowser $client;
    private string $route;


    public function setUp() : void
    {
        $this->route = '/users/2/edit';
        $this->client = static::createClient();

    }

    /**
     * @throws \Exception
     */
    public function testListAction()
    {
        $this->loginAdmin();
        $crawler = $this->client->request('GET', '/users/list');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    /**
     * @throws \Exception
     */
    public function testCreateAction(){
        $this->loginAdmin();
        $crawler = $this->client->request('GET', '/user/create');
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Ajouter')->form([
            'user[name]' => "userTest",
            'user[plainPassword][first]' => "passTest",
            'user[plainPassword][second]' => "passTest",
            'user[email]' => "userTest@test.com",
        ]);
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());


    }

    /**
     * @throws \Exception
     */
    public function testEditAction()
    {
        $this->loginAdmin();

        $crawler = $this->client->request('GET', $this->route);
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'user[name]' => 'userEditTest',
            'user[plainPassword][first]' => 'userEditPassword',
            'user[plainPassword][second]' => 'userEditPassword',
            'user[email]' => 'userEditTest@test2.fr',
            'user[role]' => 'ROLE_USER'
        ]);
        self::getContainer()->get(UserRepository::class);


        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
