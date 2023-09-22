<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Employeur;
use App\Entity\IntresstedOffre;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\IntresstedOffreRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offre')]
class OffreController extends AbstractController
{
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        /** @var Employeur $employeur */
        $employeur = $this->getUser();
        $offres = $employeur->getOffres();
        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'left_menu' => 'Mes offres',
        ]);
    }

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var Employeur $employeur */
        $employeur = $this->getUser();
        $offre = new Offre();
        $offre->setEmployeur($employeur);
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
            'left_menu' => 'Mes offres',
        ]);
    }

    #[Route('/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'left_menu' => 'Mes offres',
        ]);
    }

    #[Route('/view/{id}', name: 'app_offre_view_show', methods: ['GET'])]
    public function view(Offre $offre, Request $request, EntityManagerInterface $em, IntresstedOffreRepository $intresstedOffreRepository): Response
    {
        $intressted = $request->query->get('intressted', '0');
        $candidat = $this->getUser();


        if ($candidat instanceof Candidat) {
            $intresstedOffre = $intresstedOffreRepository->findOneBy(['candidat'=>$candidat,'offre'=>$offre]);
            if ($intressted === '1') {
                if(!$intresstedOffre) {
                    $intresstedOffre = new IntresstedOffre();
                    $intresstedOffre->setCandidat($candidat);
                    $intresstedOffre->setOffre($offre);
                    $intresstedOffre->setStatus('En attente');
                }
                $currentDateTime = new \DateTime();
                $intresstedOffre->setDate($currentDateTime);
                $em->persist($intresstedOffre);
            } elseif ($intressted === '0' && $intresstedOffre) {
                $candidat->removeIntresstedOffre($intresstedOffre);
                $intressted='0';
            }

            $em->flush();
        }

        return $this->render('offre/view.html.twig', [
            'offre' => $offre,
            'intressted' => $intressted,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
            'left_menu' => 'Mes offres',
        ]);
    }

    #[Route('/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
