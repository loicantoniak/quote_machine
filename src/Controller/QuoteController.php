<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Event\QuoteCreated;
use App\Form\QuoteType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class QuoteController extends AbstractController
{
    /**
     * @Route("/quotes", name="quotePage")
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        /*Récupère l'ensemble des citations dans la BD*/
        $queryBuilder = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->createQueryBuilder('q');

        $search = $request->query->get('search');

        /*Recherche des citations grâce à la barre de recherche*/
        if ($search) {
            $queryBuilder = $queryBuilder->where('q.content like :content')
                ->setParameter('content', '%'.$search.'%')
                ->orWhere('q.meta like :meta')
                ->setParameter('meta', '%'.$search.'%');
        }

        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5);

        /*Rendu de la vue*/
        return $this->render('quote/index.html.twig', [
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    /**
     * @Route("/quote/{id}", name="quote_show", methods={"GET"})
     */
    public function show(Quote $quote): Response
    {
        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
        ]);
    }

    /**
     * @Route("/quotes/new", name="newQuote")
     * @IsGranted("ROLE_USER")
     *
     * @return RedirectResponse|Response
     */
    public function new(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $quote = new Quote();

        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            $quote->setAuthor($user);

            $entityManager->persist($quote);
            $entityManager->flush();

            $event = new QuoteCreated($quote);
            $eventDispatcher->dispatch($event);

            return $this->redirectToRoute('quotePage');
        }

        return $this->render('quote/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("edit", subject="quote")
     * @Route("/quotes/{id}/edit", name="editQuote")
     *
     * @return RedirectResponse|Response
     */
    public function edit(Quote $quote, Request $request)
    {
        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('quotePage');
        }

        return $this->render('quote/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/quotes/{id}/delete", name="deleteQuote")
     * @IsGranted("delete", subject="quote")
     *
     * @return RedirectResponse|Response
     */
    public function delete(Quote $quote)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($quote);
        $entityManager->flush();

        return $this->redirectToRoute('quotePage');
    }

    /**
     * @Route("/quotes/random", name="randomQuote")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function random()
    {
        /*Récupère l'ensemble des citations dans la BD*/
        $quotes = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->findAll();

        $key = array_rand($quotes);
        $random = $quotes[$key];

        return $this->render('quote/random.html.twig', [
            'quote' => $random,
        ]);
    }
}
