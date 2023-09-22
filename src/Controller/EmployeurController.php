<?php

namespace App\Controller;

use App\Form\EmployeurRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeurController extends AbstractController
{
    #[Route('/employeur', name: 'app_employeur')]
    public function index(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $employeur = $this->getUser();
        $form = $this->createForm(EmployeurRegistrationFormType::class, $employeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }


        return $this->render('employeur/index.html.twig', [
            'left_menu' => "profile",
            'form' => $form,
        ]);
    }
}
