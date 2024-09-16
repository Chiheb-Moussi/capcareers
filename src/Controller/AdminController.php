<?php

namespace App\Controller;

use App\Entity\IntresstedCandidats;
use App\Entity\IntresstedOffre;
use App\Repository\CandidatInfoRepository;
use App\Repository\IntresstedCandidatsRepository;
use App\Repository\IntresstedOffreRepository;
use App\Repository\UserRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        UserRepository $userRepository,
        OffreRepository $offreRepository,
        CandidatInfoRepository $candidatInfoRepository,
        IntresstedOffreRepository $intresstedOffreRepository,
        IntresstedCandidatsRepository $intresstedCandidatsRepository
    ): Response
    {
        $countCandidat=$userRepository->countCandidat();
        $countEmployeur=$userRepository->countEmployeur();
        $countOffre=$offreRepository->countOffre();
        $countOffreEnAttente=$offreRepository->countOffreEnAttente();
        $countOffreAccepte=$offreRepository->countOffreAccepte();
        $countOffreRefuser=$offreRepository->countOffreRefuser();
        $chartDataFromController = [$countOffreAccepte,$countOffreRefuser, $countOffreEnAttente];
        $currentYear = (int) date('Y');
        $offresCounts = $offreRepository->countOffresByMonthOfYear($currentYear);
        $initialMonthsValue = [
            '01' => 0,
            '02' => 0,
            '03' => 0,
            '04' => 0,
            '05' => 0,
            '06' => 0,
            '07' => 0,
            '08' => 0,
            '09' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0,
        ];
        $offresCountsByMonth = $initialMonthsValue;
        foreach ($offresCounts as $val) {
            $offresCountsByMonth[$val['month']] = $val['offre_count'];
        }

        $contratCounts = $offreRepository->countByTypeContrat();
        $secteurCounts = $offreRepository->countBySecteur();

        $cvCount = $candidatInfoRepository->countCvByMonthOfYear($currentYear);
        $cvCountByMonth = $initialMonthsValue;
        foreach ($cvCount as $val) {
            $cvCountByMonth[$val['month']] = $val['cv_count'];
        }

        // quel sont les secteurs des offres ou il y a beaucoup de matching
        $intresstedOffres = $intresstedOffreRepository->findAll();
        $dataMatchingOffreSecteur = [];
        $dataMatchingCandidatSkills = [];
        foreach ($intresstedOffres as $intresstedOffre) {
            $employeur = $intresstedOffre->getOffre()->getEmployeur();
            $candidat = $intresstedOffre->getCandidat();
            if ($candidat && $employeur) {
                $matching = $intresstedCandidatsRepository->findOneBy(['employeur'=>$employeur, 'candidat' => $candidat]);
                if ($matching !== null) {
                    $offre = $intresstedOffre->getOffre();
                    $secteur = $offre->getSecteur();

                    if ($secteur !== null) {
                        if (array_key_exists($secteur->getTitre(), $dataMatchingOffreSecteur)) {
                            $dataMatchingOffreSecteur[$secteur->getTitre()] += 1;
                        }else {
                            $dataMatchingOffreSecteur[$secteur->getTitre()] = 1;
                        }

                        $skills = $offre->getSkills();
                        foreach ($skills as $skill) {
                            if (array_key_exists($skill->getTitre(), $dataMatchingCandidatSkills)) {
                                $dataMatchingCandidatSkills[$skill->getTitre()] += 1;
                            }else {
                                $dataMatchingCandidatSkills[$skill->getTitre()] = 1;
                            }
                        }
                    }
                }
            }
        }


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'candidat'=>$countCandidat,
            'employeur'=>$countEmployeur,
            'offre'=>$countOffre,
            'offreEnAttente'=>$countOffreEnAttente,
            'chartDataFromController' => json_encode($chartDataFromController),
            'offresCounts'=>json_encode(array_values($offresCountsByMonth)),
            'contratCounts'=>json_encode($contratCounts),
            'secteurCounts'=>json_encode($secteurCounts),
            'cvCount'=>json_encode(array_values($cvCountByMonth)),
            'dataMatchingOffreSecteur' => json_encode($dataMatchingOffreSecteur),
            'dataMatchingCandidatSkills' => json_encode($dataMatchingCandidatSkills),
        ]);
    }

    #[Route('/admin/candidats', name: 'app_admin_candidats')]
    public function candidats(IntresstedOffreRepository $intresstedOffreRepository, IntresstedCandidatsRepository $intresstedCandidatsRepository): Response
    {
        //$intresstedOffres ça represente la liste des candidats qui sont intéressé par des des offres
        $intresstedOffres = $intresstedOffreRepository->findAll();
        $data = [];
        foreach ($intresstedOffres as $intresstedOffre) {
            $employeur = $intresstedOffre->getOffre()->getEmployeur();
            $candidat = $intresstedOffre->getCandidat();
            if ($candidat) {
                //ici on verifie si il y a matching entre le candidat, l'offre  avec le l'employeur et le candidat interessé
                $matching = $intresstedCandidatsRepository->findOneBy(['employeur'=>$employeur, 'candidat' => $candidat]);
                $data[] = [
                    'id' => $intresstedOffre->getId(),
                    'candidat' => $candidat,
                    'offre' => $intresstedOffre->getOffre(),
                    'employeur' => $employeur,
                    'matching' => ($matching !== null),// on revoie une valeur boolean pour verifier si il'ya matching
                    'status' => $intresstedOffre->getStatusHtml(),
                ];
            }
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
            $offres = $employeur ? $employeur->getOffres() : [];
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
