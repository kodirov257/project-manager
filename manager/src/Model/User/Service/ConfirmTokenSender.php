<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;

class ConfirmTokenSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment     $twig,
        private readonly array           $from,
    )
    {
    }

    public function send(Email $email, string $token): void
    {
        try {
            $message = (new SymfonyEmail())
                ->from(new Address($this->from['mail'], $this->from['name']))
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