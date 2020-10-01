<?php

namespace App\Tests\Command;

use App\Entity\Category;
use App\Entity\Quote;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RandomQuoteCommandTest extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    public function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->application = new Application($kernel);
    }

    public function test_it_display_warning_when_no_quote_in_database(): void
    {
        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Aucune citation trouvée', $output);
    }

    public function test_it_display_random_quote(): void
    {
        $quote = (new Quote())
            ->setContent('C’est pas faux.')
            ->setMeta('Perceval')
            ->setAuthor($this->createUser());
        $this->persistAndFlush($quote);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('C’est pas faux.', $output);
    }

    public function test_it_display_random_quote_filtered_by_category(): void
    {
        $user = $this->createUser();

        $quote = (new Quote())
            ->setContent('C’est pas faux.')
            ->setMeta('Perceval')
            ->setCategory($this->createCategory())
            ->setAuthor($user);
        $this->persistAndFlush($quote);

        $quote = (new Quote())
            ->setContent('Comment est votre blanquette ?')
            ->setMeta('OSS 117')
            ->setAuthor($user);
        $this->persistAndFlush($quote);

        $command = $this->application->find('app:random-quote');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--category' => 'Kaamelott',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('C’est pas faux.', $output);
    }

    private function createUser(): User
    {
        $user = (new User())
            ->setName('Perceval')
            ->setPassword('le code')
            ->setEmail('perceval@kaamelott');

        $this->persistAndFlush($user);

        return $user;
    }

    private function createCategory(): Category
    {
        $category = (new Category())
            ->setName('Kaamelott');

        $this->persistAndFlush($category);

        return $category;
    }

    private function persistAndFlush($entity): void
    {
        $em = $this->application->getKernel()
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $em->persist($entity);
        $em->flush();
    }
}
