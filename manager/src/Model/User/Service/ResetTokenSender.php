<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;

class ResetTokenSender
{
    public function __construct(
        private readonly TransportInterface $mailer,
        private readonly Environment     $twig,
    )
    {
    }

    public function send(Email $email, ResetToken $token): void
    {
        try {
            $message = (new SymfonyEmail())
                ->to($email->getValue())
                ->subject('Password resetting')
                ->html($this->twig->render('mail/user/reset.html.twig', [
                    'token' => $token->getToken(),
                ]));

            $this->mailer->send($message);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}