<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;

class SignUpConfirmTokenSender
{
    public function __construct(
        private readonly TransportInterface $mailer,
        private readonly Environment     $twig,
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        try {
            $message = (new SymfonyEmail())
                ->to($email->getValue())
                ->subject('Sign Up Confirmation')
                ->html($this->twig->render('mail/user/signup.html.twig', [
                    'token' => $token,
                ]));

            $this->mailer->send($message);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}