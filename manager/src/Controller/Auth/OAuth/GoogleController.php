<?php

declare(strict_types=1);

namespace App\Controller\Auth\OAuth;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GoogleController extends AbstractController
{
    #[Route('/oauth/google', name: 'oauth.google')]
    public function connect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect(['openid', 'profile']);
    }

    #[Route('/oauth/google/check', name: 'oauth.google_check')]
    public function check(): Response
    {
        return $this->redirectToRoute('home');
    }
}