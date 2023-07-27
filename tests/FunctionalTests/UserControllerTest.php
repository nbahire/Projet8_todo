<?php

namespace App\Tests\FunctionalTests;

use App\Repository\UserRepository;
use App\Tests\FunctionalTests\Traits\AuthentificationTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
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
    public function testUserListAction()
    {
        $this->loginAdmin();
        $this->client->request('GET', '/users/list');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    /**
     * @throws \Exception
     */
    public function testUserCreateAction()
    {
        $this->loginAdmin();
        $this->client->request('GET', '/users/create');
        $this->userSubmitForm('Ajouter');
    }

    /**
     * @throws \Exception
     */
    public function testUserEditAction()
    {
        $this->loginAdmin();
        $this->client->request('GET', '/users/2/edit');
        $this->userSubmitForm('Modifier');

    }

    private function userSubmitForm(string $button): void
    {
        $this->client->submitForm($button, [
            'user[username]' => "userTest",
            'user[password][first]' => "passTest",
            'user[password][second]' => "passTest",
            'user[email]' => "userTest@test.com",
        ]);
        $this->assertResponseRedirects('/users/list', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert.alert-success');

    }
}
