<?php

namespace App\Controller;

use App\Entity\IntresstedCandidats;
use App\Entity\IntresstedOffre;
use App\Repository\IntresstedCandidatsRepository;
use App\Repository\IntresstedOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/candidats', name: 'app_admin_candidats')]
    public function candidats(IntresstedOffreRepository $intresstedOffreRepository, IntresstedCandidatsRepository $intresstedCandidatsRepository): Response
    {
        $intresstedOffres = $intresstedOffreRepository->findAll();
        $data = [];
        foreach ($intresstedOffres as $intresstedOffre) {
            $employeur = $intresstedOffre->getOffre()->getEmployeur();
            $candidat = $intresstedOffre->getCandidat();
            $matching = $intresstedCandidatsRepository->findOneBy(['employeur'=>$employeur, 'candidat' => $candidat]);
            $data[] = [
                'id' => $intresstedOffre->getId(),
                'candidat' => $candidat,
                'offre' => $intresstedOffre->getOffre(),
                'employeur' => $employeur,
                'matching' => ($matching !== null),
                'status' => $intresstedOffre->getStatusHtml(),
            ];
        }
        return $this->render('admin/candidats.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/admin/candidats/{id}', name: 'app_admin_candidat_view')]
    public function viewCandidat(IntresstedOffre $intresstedOffre, IntresstedCandidatsRepository $intresstedCandidatsRepository, Request $request, EntityManagerInterface $em): Response
    {
        $status = $request->query->get('status', '');
        if($status) {
            $intresstedOffre->setStatus($status);
            $em->flush();
        }

        $employeur = $intresstedOffre->getOffre()->getEmployeur();
        $candidat = $intresstedOffre->getCandidat();
        $matching = $intresstedCandidatsRepository->findOneBy(['employeur'=>$employeur, 'candidat' => $candidat]);

        return $this->render('admin/view-candidat.html.twig', [
            'id' => $intresstedOffre->getId(),
            'candidat' => $candidat,
            'offre' => $intresstedOffre->getOffre(),
            'employeur' => $employeur,
            'matching' => ($matching !== null),
            'status' => $intresstedOffre->getStatusHtml(),
        ]);
    }


    #[Route('/admin/employeurs', name: 'app_admin_employeurs')]
    public function employeurs(IntresstedOffreRepository $intresstedOffreRepository, IntresstedCandidatsRepository $intresstedCandidatsRepository): Response
    {
        $intresstedCandidats = $intresstedCandidatsRepository->findAll();
        $data = [];
        foreach ($intresstedCandidats as $intresstedCandidat) {
            $employeur = $intresstedCandidat->getEmployeur();
            $candidat = $intresstedCandidat->getCandidat();
            $offres = $employeur->getOffres();
            $matching = false;
            foreach($offres as $offre) {
                $foundMatching = $intresstedOffreRepository->findOneBy(['offre'=>$offre, 'candidat' => $candidat]);
                if($foundMatching) {
                    $matching = true;
                    break;
                }
            }
            $data[] = [
                'id' => $intresstedCandidat->getId(),
                'candidat' => $candidat,
                'employeur' => $employeur,
                'matching' => $matching,
                'status' => $intresstedCandidat->getStatusHtml(),
            ];
        }
        return $this->render('admin/employeurs.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/admin/employeurs/{id}', name: 'app_admin_employeur_view')]
    public function viewEmployeur(IntresstedCandidats $intresstedCandidat, IntresstedOffreRepository $intresstedOffreRepository, Request $request, EntityManagerInterface $em): Response
    {
        $status = $request->query->get('status', '');
        if($status) {
            $intresstedCandidat->setStatus($status);
            $em->flush();
        }

        $employeur = $intresstedCandidat->getEmployeur();
        $candidat = $intresstedCandidat->getCandidat();
        $offres = $employeur->getOffres();
        $matching = false;
        foreach($offres as $offre) {
            $foundMatching = $intresstedOffreRepository->findOneBy(['offre'=>$offre, 'candidat' => $candidat]);
            if($foundMatching) {
                $matching = true;
                break;
            }
        }

        return $this->render('admin/view-employeur.html.twig', [
            'id' => $intresstedCandidat->getId(),
            'candidat' => $candidat,
            'employeur' => $employeur,
            'matching' => $matching,
            'status' => $intresstedCandidat->getStatusHtml(),
        ]);
    }

}
