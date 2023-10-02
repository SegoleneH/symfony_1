<?php

namespace App\Test\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProjectRepository $repository;
    private string $path = '/project/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Project::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'project[name]' => 'Testing',
            'project[description]' => 'Testing',
            'project[clientName]' => 'Testing',
            'project[startDate]' => 'Testing',
            'project[checkpointDate]' => 'Testing',
            'project[deliveryDate]' => 'Testing',
            'project[createdAt]' => 'Testing',
            'project[updatedAt]' => 'Testing',
            'project[deletedAt]' => 'Testing',
            'project[students]' => 'Testing',
            'project[tags]' => 'Testing',
        ]);

        self::assertResponseRedirects('/project/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setClientName('My Title');
        $fixture->setStartDate('My Title');
        $fixture->setCheckpointDate('My Title');
        $fixture->setDeliveryDate('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setDeletedAt('My Title');
        $fixture->setStudents('My Title');
        $fixture->setTags('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setClientName('My Title');
        $fixture->setStartDate('My Title');
        $fixture->setCheckpointDate('My Title');
        $fixture->setDeliveryDate('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setDeletedAt('My Title');
        $fixture->setStudents('My Title');
        $fixture->setTags('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'project[name]' => 'Something New',
            'project[description]' => 'Something New',
            'project[clientName]' => 'Something New',
            'project[startDate]' => 'Something New',
            'project[checkpointDate]' => 'Something New',
            'project[deliveryDate]' => 'Something New',
            'project[createdAt]' => 'Something New',
            'project[updatedAt]' => 'Something New',
            'project[deletedAt]' => 'Something New',
            'project[students]' => 'Something New',
            'project[tags]' => 'Something New',
        ]);

        self::assertResponseRedirects('/project/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getClientName());
        self::assertSame('Something New', $fixture[0]->getStartDate());
        self::assertSame('Something New', $fixture[0]->getCheckpointDate());
        self::assertSame('Something New', $fixture[0]->getDeliveryDate());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getDeletedAt());
        self::assertSame('Something New', $fixture[0]->getStudents());
        self::assertSame('Something New', $fixture[0]->getTags());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Project();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setClientName('My Title');
        $fixture->setStartDate('My Title');
        $fixture->setCheckpointDate('My Title');
        $fixture->setDeliveryDate('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setDeletedAt('My Title');
        $fixture->setStudents('My Title');
        $fixture->setTags('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/project/');
    }
}
