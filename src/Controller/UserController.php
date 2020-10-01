<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="user", requirements={"id"="\d+"})
     *
     * @return Response
     */
    public function profile(User $user)
    {
        $lastQuotes = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->lastQuotes($user);

        return $this->render('user/index.html.twig', ['user' => $user]);
    }
}
