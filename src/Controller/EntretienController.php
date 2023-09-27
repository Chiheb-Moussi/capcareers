<?php

namespace App\Controller;

use App\Entity\Entretien;
use App\Entity\EntretienDate;
use App\Entity\IntresstedCandidats;
use App\Repository\CandidatRepository;
use App\Repository\EntretienDateRepository;
use App\Repository\EntretienRepository;
use App\Repository\IntresstedCandidatsRepository;
use App\Repository\IntresstedOffreRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntretienController extends AbstractController
{

    #[Route('/entretien', name: 'app_entretien')]
    public function index(EntretienRepository $entretienRepository): Response
    {
        $employeur = $this->getUser();
        $entretiens = $employeur->getEntretiens();
        $entretiensDates = [];
        foreach ($entretiens as $entretien) {
            foreach ($entretien->getEntretienDates() as $entretienDate) {
                $entretiensDates[]=$entretienDate;
            }
        }

        return $this->render('entretien/index.html.twig', [
            'entretiensDates' => $entretiensDates,
            'left_menu' => 'calendrier',
        ]);
    }

    #[Route('/entretien/candidat', name: 'app_entretien_candidat')]
    public function candidatEntretiens(EntretienRepository $entretienRepository): Response
    {
        $candidat = $this->getUser();
        $entretiens = $candidat->getEntretiens();
        $entretiensDates = [];
        foreach ($entretiens as $entretien) {
            foreach ($entretien->getEntretienDates() as $entretienDate) {
                $entretiensDates[]=$entretienDate;
            }
        }

        return $this->render('entretien/candidat-entretiens.html.twig', [
            'entretiensDates' => $entretiensDates,
            'left_menu' => 'calendrier',
        ]);
    }

    #[Route('/entretien/new', name: 'app_entretien_new')]
    public function new(IntresstedCandidatsRepository $intresstedCandidatsRepository, Request $request, OffreRepository $offreRepository, CandidatRepository $candidatRepository, EntityManagerInterface $em, IntresstedOffreRepository $intresstedOffreRepository): Response
    {
        $employeur = $this->getUser();
        if ($request->getMethod() === Request::METHOD_POST) {
            $titre = $request->request->get('titre');
            $offre_id = $request->request->get('offre');
            $candidat_id = $request->request->get('candidat');
            $duration= $request->request->get('duration');
            $date1= $request->request->get('date1');
            $date2= $request->request->get('date2');
            $date3= $request->request->get('date3');
            $offre = $offreRepository->find($offre_id);
            $candidat = $candidatRepository->find($candidat_id);

            $entretien = new Entretien();
            $entretien->setTitre($titre);
            $entretien->setOffre($offre);
            $entretien->setCandidat($candidat);
            $entretien->setEmployeur($employeur);
            $entretien->setDuration($duration);
            if ($date1) {
                $firstPropositionDate = \DateTime::createFromFormat("d/m/Y H:i", $date1);
                $firstProposition = new EntretienDate();
                $firstProposition->setDate($firstPropositionDate);
                $dateFin = clone $firstPropositionDate;
                $dateFin->add(new \DateInterval("PT".$duration."M"));
                $firstProposition->setDateFin($dateFin);
                $entretien->addEntretienDate($firstProposition);
                $em->persist($firstProposition);
            }
            if ($date2) {
                $secondPropositionDate = \DateTime::createFromFormat("d/m/Y H:i", $date2);
                $secondPoposition = new EntretienDate();
                $secondPoposition->setDate($secondPropositionDate);
                $dateFin = clone $secondPropositionDate;
                $dateFin->add(new \DateInterval("PT".$duration."M"));
                $secondPoposition->setDateFin($dateFin);
                $entretien->addEntretienDate($secondPoposition);
                $em->persist($secondPoposition);

            }
            if ($date3) {
                $thirdPropositionDate = \DateTime::createFromFormat("d/m/Y H:i", $date3);
                $thirdProposition = new EntretienDate();
                $thirdProposition->setDate($thirdPropositionDate);
                $dateFin = clone $thirdPropositionDate;
                $dateFin->add(new \DateInterval("PT".$duration."M"));
                $thirdProposition->setDateFin($dateFin);
                $entretien->addEntretienDate($thirdProposition);
                $em->persist($thirdProposition);
            }

            $em->persist($entretien);
            $em->flush();

            return $this->redirectToRoute('app_entretien');
        }


        $offres = $employeur->getOffres();
        $offresIds = [];
        foreach ($offres as $offre) {
            $offresIds[]= $offre->getId();
        }
        $candidats = [];
        $intresstedCandidats = $intresstedCandidatsRepository->findBy(['employeur' => $employeur, 'status' => IntresstedCandidats::STATUS_ACCEPTE]);
        foreach ($intresstedCandidats as $intresstedCandidat) {
            $candidats[] = $intresstedCandidat->getCandidat();
        }
        $intresstedOffres = $intresstedOffreRepository->findByOffreIds($offresIds);
        foreach ($intresstedOffres as $intresstedOffre) {
            if(!in_array($intresstedOffre->getCandidat(), $candidats)) {
                $candidats[] = $intresstedOffre->getCandidat();
            }
        }

        return $this->render('entretien/new.html.twig', [
            'offres' => $offres,
            'candidats' => $candidats,
            'left_menu' => 'calendrier'
        ]);
    }

    #[Route('/entretien/{id}', name: 'app_entretien_details')]
    public function details(Entretien $entretien)
    {
        return $this->render('entretien/details.html.twig', [
            'entretien' => $entretien,
            'left_menu' => 'calendrier'
        ]);
    }

    #[Route('/entretien/candidat/{id}', name: 'app_entretien_candidat_details')]
    public function candidatEntretienDetails(Entretien $entretien, Request $request, EntretienDateRepository $entretienDateRepository, EntityManagerInterface $em)
    {
        $entretienDateId = $request->query->get('date');
        if($entretienDateId) {
            $entretienDates = $entretien->getEntretienDates();
            foreach ($entretienDates as $entretienDate) {
                if($entretienDate->getId()==$entretienDateId) {
                    $entretienDate->setConfirmed(true);
                    $entretien->setDate($entretienDate->getDate());
                }else {
                    $em->remove($entretienDate);
                }
            }
            $em->flush();
        }
        return $this->render('entretien/candidat-entretien-details.html.twig', [
            'entretien' => $entretien,
            'left_menu' => 'calendrier'
        ]);
    }
}
