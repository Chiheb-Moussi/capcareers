<?php

namespace App\Controller;

use App\Entity\Entretien;
use App\Entity\EntretienDate;
use App\Entity\IntresstedCandidats;
use App\Repository\CandidatRepository;
use App\Repository\EntretienRepository;
use App\Repository\IntresstedCandidatsRepository;
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
        /*$entretiensDates = array_map(function($entretien){
            return $entretien->getEntretienDate()
        })*/
        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretiens,
            'left_menu' => 'calendrier',
        ]);
    }

    #[Route('/entretien/new', name: 'app_entretien_new')]
    public function new(IntresstedCandidatsRepository $intresstedCandidatsRepository, Request $request, OffreRepository $offreRepository, CandidatRepository $candidatRepository, EntityManagerInterface $em): Response
    {
        $employeur = $this->getUser();
        if ($request->getMethod() === Request::METHOD_POST) {
            $offre_id = $request->request->get('offre');
            $candidat_id = $request->request->get('candidat');
            $duration= $request->request->get('duration');
            $date1= $request->request->get('date1');
            $date2= $request->request->get('date2');
            $date3= $request->request->get('date3');
            $offre = $offreRepository->find($offre_id);
            $candidat = $candidatRepository->find($candidat_id);

            $entretien = new Entretien();
            $entretien->setOffre($offre);
            $entretien->setCandidat($candidat);
            $entretien->setEmployeur($employeur);
            if($duration) {
                $entretien->setDuration($duration);
            }
            if ($date1) {
                $firstPropositionDate = \DateTime::createFromFormat("d/m/Y H:i", $date1);
                $firstProposition = new EntretienDate();
                $firstProposition->setDate($firstPropositionDate);
                $entretien->addEntretienDate($firstProposition);
                $em->persist($firstProposition);
            }
            if ($date2) {
                $secondPropositionDate = \DateTime::createFromFormat("d/m/Y H:i", $date2);
                $secondPoposition = new EntretienDate();
                $secondPoposition->setDate($secondPropositionDate);
                $entretien->addEntretienDate($secondPoposition);
                $em->persist($secondPoposition);

            }
            if ($date3) {
                $thirdPropositionDate = \DateTime::createFromFormat("d/m/Y H:i", $date3);
                $thirdProposition = new EntretienDate();
                $thirdProposition->setDate($thirdPropositionDate);
                $entretien->addEntretienDate($thirdProposition);
                $em->persist($thirdProposition);
            }

            $em->persist($entretien);
            $em->flush();

            return $this->redirectToRoute('app_entretien');
        }


        $offres = $employeur->getOffres();
        $intresstedCandidats = $intresstedCandidatsRepository->findBy(['employeur' => $employeur, 'status' => IntresstedCandidats::STATUS_ACCEPTE]);

        return $this->render('entretien/new.html.twig', [
            'offres' => $offres,
            'intresstedCandidats' => $intresstedCandidats,
            'left_menu' => 'calendrier'
        ]);
    }
}
