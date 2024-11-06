<?php

declare(strict_types=1);

namespace App\Controller\Profile\OAuth;

use App\Model\User\UseCase\Network\Attach\Command;
use App\Model\User\UseCase\Network\Attach\Handler;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/oauth/google')]
class GoogleController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[Route('/attach', name: 'profile.oauth.google')]
    public function connect(ClientRegistry $registry): Response
    {
        return $registry->getClient('google_attach')->redirect(['openid', 'profile']);
    }

    #[Route('/check', name: 'profile.oauth.google_check')]
    public function check(ClientRegistry $registry, Handler $handler): Response
    {
        $client = $registry->getClient('google_attach');

        $command = new Command(
            $this->getUser()->getId(),
            'google',
            $client->fetchUser()->getId(),
        );

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Google is successfully attached.');
            return $this->redirectToRoute('profile');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('profile');
        }
    }
}