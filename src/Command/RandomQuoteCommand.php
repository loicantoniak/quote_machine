<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use App\Repository\QuoteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomQuoteCommand extends Command
{
    protected static $defaultName = 'app:random-quote';

    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(quoteRepository $quoteRepository, categoryRepository $categoryRepository)
    {
        $this->quoteRepository = $quoteRepository;
        $this->categoryRepository = $categoryRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Display a random quote')
            ->addOption('category', null, InputOption::VALUE_REQUIRED, 'filter by given category')
            ->setHelp('This command allows you to display a random quote')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $categoryName = $input->getOption('category');

        if (null === $categoryName) {
            $quote = $this->quoteRepository->random();

            if (null === $quote) {
                $io->warning('Aucune citation trouvée');

                return 0;
            }

            $output->writeln([
                $quote->getContent(),
                '',
                $quote->getMeta(),
            ]);

            return 0;
        }

        $category = $this->categoryRepository->findOneByName($categoryName);

        if (null === $category) {
            $io->error('La catégorie "'.$categoryName.'" est inconnue');

            return 1;
        }

        $quote = $this->quoteRepository->random($category);
        if (null === $quote) {
            $io->warning('Aucune citation trouvée');

            return 0;
        }

        $output->writeln([
            $quote->getContent(),
            '',
            $quote->getMeta(),
        ]);

        return 0;
    }
}
