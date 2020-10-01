<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    private function createAdmin()
    {
        $email = 'admin@quote-machine.fr';
        $password = 'admin';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => $email,
            'PHP_AUTH_PW' => $password,
        ]);

        $user = new User();
        $user->setName('admin1');
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $client->getContainer()->get('security.password_encoder')->encodePassword($user, $password)
        );

        $entityManager = self::$container->get('doctrine')->getManagerForClass(User::class);
        $entityManager->persist($user);
        $entityManager->flush();

        return $client;
    }

    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/category/top');
        $this->assertSelectorTextContains('body', 'Aucune categorie');
    }

    public function testNewCategory()
    {
        $client = static::createAdmin();
        $client->followRedirects();

        $client->request('GET', '/category/new');

        $client->submitForm('Ajouter',
            ['category[name]' => 'Catégorie test',
            ]);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('quotePage');

        // Récupère le repository pour l'entité MyEntity
        $repository = self::$container->get('doctrine')->getRepository(Category::class);

        $this->assertSame(1, $repository->count(['name' => 'Catégorie test']));
    }

    public function testEditCategory()
    {
        $client = static::createAdmin();
        $client->followRedirects();
        $entityManager = self::$container->get('doctrine')->getManagerForClass(Category::class);
        $category1 = new Category();
        $category1->setName('test');
        $entityManager->persist($category1);
        $entityManager->flush();

        $client->request('GET', '/category/'.$category1->getId().'/edit');
        $client->submitForm('Modifier',
            ['category[name]' => 'test2',
            ]);

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('quotePage');

        $repository = self::$container->get('doctrine')->getRepository(Category::class);

        $this->assertSame(1, $repository->count(['name' => 'test2']));
        $this->assertSame(0, $repository->count(['name' => 'test']));
    }

    public function testDeleteCategory()
    {
        $client = static::createAdmin();
        $client->followRedirects();
        $entityManager = self::$container->get('doctrine')->getManagerForClass(Category::class);
        $category1 = new Category();
        $category1->setName('test');
        $entityManager->persist($category1);
        $entityManager->flush();

        $client->request('GET', '/category/'.$category1->getId().'/delete');

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame('quotePage');

        $repository = self::$container->get('doctrine')->getRepository(Category::class);

        $this->assertSame(0, $repository->count(['name' => 'test']));
    }
}
