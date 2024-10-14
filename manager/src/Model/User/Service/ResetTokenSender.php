<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;

class ResetTokenSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment     $twig,
        private readonly array           $from,
    )
    {
    }

    public function send(Email $email, ResetToken $token): void
    {
        try {
            $message = (new SymfonyEmail())
                ->from(new Address($this->from['mail'], $this->from['name']))
                ->to($email->getValue())
                ->subject('Password resetting')
                ->html($this->twig->render('mail/user/reset.html.twig', [
                    'token' => $token,
                ]));

            $this->mailer->send($message);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Unable to send message.');
        }
    }
}