<?php

namespace App\Tests\FunctionalTests;

use App\Tests\FunctionalTests\Traits\AuthentificationTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
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
    public function testTaskCreateAction()
    {
        $this->loginUser();
        $this->client->request('GET', '/tasks/create');
        $this->client->submitForm('Ajouter', [
            'task[title]'=>'TaskTitleTest',
            'task[content]'=>'TaskContentTest'
        ]);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert-success', "La tâche a été bien été ajoutée.");
    }
    /**
     * @throws \Exception
     */
    public function testTaskListAction()
    {
        $this->loginUser();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testTaskEditAction()
    {
        $this->loginUser();
        $this->client->request('GET', '/tasks/12/edit');
        $this->client->submitForm('Modifier', [
            'task[title]' => 'Hello world',
            'task[content]' => 'Contenu modifié',
        ]);
        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été modifiée.");
    }

    /**
     * @throws \Exception
     */
    public function testToggleTaskAction()
    {
        $this->loginUser();

        $this->client->request('GET', '/tasks/14/toggle');
        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $crawler = $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }

    /**
     * @throws \Exception
     */
    public function testDeniedDeleteTaskAction()
    {
        $this->loginUser();

        $this->client->request('GET', '/tasks/1/delete');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }


    /**
     * @throws \Exception
     */
    public function testAdminDeleteTaskAction()
    {
        $this->loginAdmin();

        $this->client->request('GET', '/tasks/80/delete');
        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }

    /**
     * @throws \Exception
     */
    public function testDeleteAnonymousTaskAction()
    {
        $this->loginAdmin();

        $this->client->request('GET', '/tasks/5/delete');
        $this->assertResponseRedirects('/tasks', Response::HTTP_FOUND);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }

    /**
     * @throws \Exception
     */
    public function testFailDeleteTaskAction()
    {
        $this->loginAdmin();

        $this->client->request('GET', '/tasks/48/delete');
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());

    }

}
