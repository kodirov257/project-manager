<?php

namespace App\Controller;

use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(private readonly UserFetcher $users)
    {
    }

    #[Route('', name: 'users')]
    public function index(): Response
    {
        $users = $this->users->all();

        return $this->render('app/users/index.html.twig', compact('users'));
    }
}