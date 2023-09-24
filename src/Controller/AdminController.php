<?php

namespace App\Controller;

use App\Entity\IntresstedOffre;
use App\Repository\IntresstedCandidatsRepository;
use App\Repository\IntresstedOffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function viewCandidat(IntresstedOffre $intresstedOffre, IntresstedCandidatsRepository $intresstedCandidatsRepository): Response
    {
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

}
