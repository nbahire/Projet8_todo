<?php

namespace App\Tests\Controller;
use App\Repository\TaskRepository;
use App\Tests\Controller\Traits\AuthentificationTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    use AuthentificationTrait;
    private KernelBrowser $client;
    private string $route;

    public function setUp(): void
    {
        $this->route = '/tasks/90/edit';
        $this->client = static::createClient();
    }

    /**
     * @throws \Exception
     */
    public function testTaskCreateAction()
    {
        $this->loginUser();
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]'=>'test5555',
            'task[content]'=>'lorem5555'
        ]);

        $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), $this->client->getResponse()->getContent());

        $this->assertSelectorTextContains('div.alert-success', "Superbe ! Votre tache a bien été envoyé");
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

        $crawler = $this->client->request('GET', '/tasks/90/edit');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function testTtoggleTaskAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/tasks/9/toggle');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
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

        $this->client->request('GET', '/tasks/90/delete');

        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }


    /**
     * @throws \Exception
     */
    public function testDeleteTaskAction()
    {
        $this->loginAdmin();

        $this->client->request('GET', '/tasks/160/delete');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testDeleteAnonymousTaskAction()
    {
        $this->loginAdmin();

        $this->client->request('GET', '/tasks/79/delete');
        $this->assertEquals(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testFailDeleteTaskAction()
    {
        $this->loginAdmin();

        $this->client->request('GET', '/tasks/48/delete');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

    }

}
