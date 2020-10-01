<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    private function createUser()
    {
        $email = 'user@quote-machine.fr';
        $password = 'user';

        $client = static::createClient([], [
            'PHP_AUTH_USER' => $email,
            'PHP_AUTH_PW' => $password,
        ]);

        $user = new User();
        $user->setName('user1');
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
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
        $client->request('GET', '/quotes');
        $this->assertSelectorTextContains('body', 'Aucune citation');
    }

    public function testNewQuote()
    {
        $client = static::createUser();
        $client->followRedirects();

        $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Ajouter',
            ['quote[content]' => 'Citation test',
                'quote[meta]' => 'Loïc',
            ]);

        $this->assertRouteSame('quotePage');

        $this->assertSelectorTextContains('body', 'Citation test');
    }

    public function testEditQuote()
    {
        $client = static ::createUser();
        $client->followRedirects();

        $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm('Ajouter',
            ['quote[content]' => 'Citation test',
                'quote[meta]' => 'Loïc',
            ]);

        $this->assertRouteSame('quotePage');

        $link = $crawler->selectLink('Modifier')->link();
        $client->click($link);

        $this->assertRouteSame('editQuote');

        $client->submitForm('Mettre à jour',
            ['quote[content]' => 'Citation test 2',
                'quote[meta]' => 'Développeur',
            ]);

        $this->assertRouteSame('quotePage');

        $this->assertSelectorTextContains('body', 'Citation test 2');
    }

    public function testDeleteQuote()
    {
        $client = static ::createUser();
        $client->followRedirects();

        $client->request('GET', '/quotes/new');

        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm('Ajouter',
            ['quote[content]' => 'Citation test',
                'quote[meta]' => 'Loïc',
            ]);

        $this->assertRouteSame('quotePage');

        $link = $crawler->selectLink('Supprimer')->link();
        $client->click($link);

        $this->assertSelectorTextContains('body', 'Aucune citation');
    }
}
