<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        /* @var InMemoryUserProvider */ private readonly UserProviderInterface $userProvider,
    )
    {
    }

    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        if (!\is_string($credentials['email']) && !$credentials['email'] instanceof \Stringable) {
            throw new BadRequestHttpException(sprintf('The key "email" must be a string, "%s" given.', \gettype($credentials['email'])));
        }

        $credentials['email'] = trim($credentials['email']);

        if ($credentials['email'] === '') {
            throw new BadCredentialsException('The key "email" must be a non-empty string.');
        }

        if (!filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            throw new BadCredentialsException('The key "email" must be a valid email address.');
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $credentials['email']);

        if (!\is_string($credentials['password']) && (!\is_object($credentials['password']) || !method_exists($credentials['password'], '__toString'))) {
            throw new BadRequestHttpException(sprintf('The key "password" must be a string, "%s" given.', \gettype($credentials['password'])));
        }

        if ('' === (string) $credentials['password']) {
            throw new BadCredentialsException('The key "password" must be a non-empty string.');
        }

        if (!\is_string($credentials['csrf_token'] ?? '') && (!\is_object($credentials['csrf_token']) || !method_exists($credentials['csrf_token'], '__toString'))) {
            throw new BadRequestHttpException(sprintf('The key "_csrf_token" must be a string, "%s" given.', \gettype($credentials['csrf_token'])));
        }

        return $credentials;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true
        throw new \Exception('TODO: check the credentials inside' . __FILE__);
    }

    /**
     * @throws \Exception
     */
    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);

        $this->checkCredentials($credentials, $user = $this->userProvider->loadUserByIdentifier($credentials['email']));

        return new Passport(
            new UserBadge($credentials['email'], $user),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
