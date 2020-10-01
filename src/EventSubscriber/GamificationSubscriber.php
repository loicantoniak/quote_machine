<?php

namespace App\EventSubscriber;

use App\Entity\Quote;
use App\Event\QuoteCreated;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GamificationSubscriber implements EventSubscriberInterface
{
    public const QUOTE_CREATED_EXP = 100;
    public const QUOTE_CREATED_FIRST_TIME_CATEGORY_EXP = 120;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onQuoteCreated(QuoteCreated $event)
    {
        $quote = $event->getQuote();
        $category = $quote->getCategory();

        $quotes = $this->entityManager->getRepository(Quote::class)->findBy([
            'Author' => $quote->getAuthor()->getId(),
            'category' => $category->getId(),
        ]);

        if (1 === count($quotes)) {
            $quote->getAuthor()->addExperience(self::QUOTE_CREATED_FIRST_TIME_CATEGORY_EXP);
        } else {
            $quote->getAuthor()->addExperience(self::QUOTE_CREATED_EXP);
        }

        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            QuoteCreated::class => 'onQuoteCreated',
        ];
    }
}
