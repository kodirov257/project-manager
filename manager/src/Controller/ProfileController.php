<?php

namespace App\Controller;

use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    public function __construct(private readonly UserFetcher $users)
    {
    }

    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        $user = $this->users->findDetail($this->getUser()->getId());

        return $this->render('app/profile/show.html.twig', compact('user'));
    }
}