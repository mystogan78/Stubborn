<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use App\Entity\Utilisateur;


final class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
    ) {}

    public function sendEmailConfirmation(string $verifyRouteName, Utilisateur $user, TemplatedEmail $email): void
    {
        $signature = $this->verifyEmailHelper->generateSignature(
            $verifyRouteName,
            (string) $user->getId(),
            (string) $user->getEmail(),
            ['id' => $user->getId()]
        );

        // Complète le contexte du template
        $context = $email->getContext();
        $context['signedUrl'] = $signature->getSignedUrl();
        $context['expiresAtMessageKey'] = $signature->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signature->getExpirationMessageData();
        $context['user'] = $user; // 👈 indispensable pour {{ user.email }}

        $email->context($context);

        $this->mailer->send($email);
    }

    public function handleEmailConfirmation(Request $request, Utilisateur $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest(
        $request,
        (string) $user->getId(),
        (string) $user->getEmail()
);


        $user->setIsVerified(true);
        $this->em->flush();
    }
}
