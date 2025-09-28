<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;            // <= AJOUT
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier) {}

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
           dd((string) $form->getErrors(true, false));
    }


        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = (string) $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));

            $em->persist($user);
            $em->flush();

            // Email de confirmation
            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@monsite.test', 'Ma Boutique'))
                ->to($user->getEmail())
                ->subject('Confirmez votre inscription')
                ->htmlTemplate('register/confirmation_email.html.twig')
                ->context(['user' => $user]);

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);

            $this->addFlash('success', 'Compte créé ! Vérifiez votre e-mail pour confirmer.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('register/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyEmail(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $id = $request->query->get('id');
        if (!$id) {
            $this->addFlash('danger', 'Lien invalide.');
            return $this->redirectToRoute('app_home');
        }

        $user = $em->getRepository(Utilisateur::class)->find($id);
        if (!$user) {
            $this->addFlash('danger', 'Utilisateur introuvable.');
            return $this->redirectToRoute('app_home');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (\Throwable) {
            $this->addFlash('danger', 'Le lien de confirmation est invalide ou expiré.');
            return $this->redirectToRoute('app_home');
        }

        // ✅ Auto-login sans authenticator (nom du firewall ci-dessous)
        $security->login($user, 'security.authenticator.form_login.main');

        $this->addFlash('success', 'Votre e-mail est confirmé. Bienvenue !');
        return $this->redirectToRoute('app_home');
    }
}
