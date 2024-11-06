<?php

declare(strict_types=1);

namespace App\Security\OAuth;

use App\Model\User\UseCase\Network\Auth\Command;
use App\Model\User\UseCase\Network\Auth\Handler;
use App\Security\UserProvider;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly ClientRegistry $clients,
        /* @var UserProvider */ private readonly UserProviderInterface $userProvider,
        private readonly Handler $handler,
    )
    {
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'oauth.google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $accessToken = $this->getCredentials($request);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken) {
                return $this->getUser($accessToken);
            }),
        );
    }

    public function getCredentials(Request $request): AccessToken
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    private function getUser($credentials): UserInterface
    {
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $network = 'google';
        $id = $googleUser->getId();
        $username = $network . ':' . $id;

        $command = new Command($network, $id);
        $command->firstName = $googleUser->getFirstName();
        $command->lastName = $googleUser->getLastName();

        try {
            return $this->userProvider->loadUserByIdentifier($username);
        } catch (UserNotFoundException $e) {
            $this->handler->handle($command);
            return $this->userProvider->loadUserByIdentifier($username);
        }
    }

    private function getGoogleClient(): OAuth2ClientInterface
    {
        return $this->clients->getClient('google');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}