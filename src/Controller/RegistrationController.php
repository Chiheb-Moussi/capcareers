<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Employeur;
use App\Form\CandidatRegistrationFormType;
use App\Form\EmployeurRegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register() :Response
    {
        return $this->render('registration/index.html.twig');
    }

    #[Route('/register-candidat', name: 'app_register_candidat')]
    public function registerCandidat(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatRegistrationFormType::class, $candidat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $candidat->setPassword(
                $userPasswordHasher->hashPassword(
                    $candidat,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($candidat);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $candidat,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@capcareers.com', 'no-reply'))
                    ->to($candidat->getEmail())
                    ->subject('Veuillez confirmer votre E-mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_check_email');
        }

        return $this->render('registration/register-candidat.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register-employeur', name: 'app_register_employeur')]
    public function registerEmployeur(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $employeur = new Employeur();
        $form = $this->createForm(EmployeurRegistrationFormType::class, $employeur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $employeur->setPassword(
                $userPasswordHasher->hashPassword(
                    $employeur,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($employeur);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $employeur,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@capcareers.com', 'no-reply'))
                    ->to($employeur->getEmail())
                    ->subject('Veuillez confirmer votre E-mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_check_email');
        }

        return $this->render('registration/register-employeur.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_home');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_home');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/check/email', name: 'app_check_email')]
    public function checkEmail() :Response
    {
        return $this->render('registration/check_email.html.twig');
    }
}
