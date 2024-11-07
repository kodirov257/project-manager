<?php

namespace App\Controller;

use App\Model\User\Entity\User\User;
use App\ReadModel\User\UserFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
class UserController extends AbstractController
{

    #[Route('', name: 'users')]
    public function index(Request $request, UserFetcher $users): Response
    {
        $users = $users->all();

        return $this->render('app/users/index.html.twig', compact('users'));
    }

    #[Route('/{id}', name: 'users.show')]
    public function show(User $user): Response
    {
        return $this->render('app/users/show.html.twig', compact('user'));
    }
}