<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\CandidatInfo;
use App\Entity\Employeur;
use App\Entity\IntresstedCandidats;
use App\Entity\IntresstedOffre;
use App\Entity\Offre;
use App\Form\CandidatInfoType;
use App\Form\CandidatRegistrationFormType;
use App\Repository\CandidatInfoRepository;
use App\Repository\IntresstedCandidatsRepository;
use App\Repository\IntresstedOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/candidat/info')]
class CandidatInfoController extends AbstractController
{
    #[Route('/', name: 'app_candidat_info_index', methods: ['GET'])]
    public function index(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $candidat = $this->getUser();
        $form = $this->createForm(CandidatRegistrationFormType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

        }


        return $this->render('candidat_info/index.html.twig', [
            'left_menu' => "profile",
            'form' => $form,
        ]);
    }


    #[Route('/edit', name: 'app_candidat_info_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
     
        /** @var Candidat $candidat */
        $candidat = $this->getUser();
        $candidatInfo = $candidat->getCandidatInfo();

        $form = $this->createForm(CandidatInfoType::class, $candidatInfo);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $cvFile = $request->files->get('cv');
          //  dd($this->getParameter('kernel.project_dir') . '/public/uploads');
            if ($cvFile instanceof UploadedFile) {
                $uniqueFolderName = $candidat->getId();

                // Define the upload directory with the unique folder name
                $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $uniqueFolderName;
    
                // Create the directory if it doesn't exist
                if (!file_exists($uploadDirectory)) {
                    mkdir($uploadDirectory, 0755, true); // Recursive directory creation
                }
                // Handle the uploaded file here
                // ...
                $newFileName = $cvFile->getClientOriginalName();

                // Move the uploaded file to the public/uploads directory
               
                $cvFile->move($uploadDirectory, $newFileName);
                $candidatInfo->setCv($newFileName);
            }
            
            $entityManager->flush();
        }
       
        return $this->render('candidat_info/edit.html.twig', [
            'candidat_info' => $candidatInfo,
            'form' => $form,
            'left_menu'=> 'Mes informations',
            'cv'=>$candidatInfo->getCv()
        ]);
    }

    #[Route('/{id}', name: 'app_candidat_view_show', methods: ['GET'])]
    public function view(Candidat $candidat, Request $request, EntityManagerInterface $em, IntresstedCandidatsRepository $intresstedCandidatsRepository): Response
    {
        $intressted = $request->query->get('intressted', '0');
        $employeur = $this->getUser();
        //l'employeur par défaut il ne peux voir les informations privées de candidats
        $canSeeCandidatPrivateInfo = false;
        if ($employeur instanceof Employeur) {
            $intresstedCandidats = $intresstedCandidatsRepository->findOneBy(['employeur'=>$employeur,'candidat'=>$candidat]);
            //si l'employeur connecté est intéressé par le profile de candidat et le cap a accepté de donner le droit à l'employeur de voir les infos de candidat
            $canSeeCandidatPrivateInfo = $intresstedCandidats !==null &&  $intresstedCandidats->getStatus() === IntresstedCandidats::STATUS_ACCEPTE;

            if ($intressted === '1') {
                if(!$intresstedCandidats) {
                    $intresstedCandidats = new IntresstedCandidats();
                    $intresstedCandidats->setEmployeur($employeur);
                    $intresstedCandidats->setCandidat($candidat);
                    $intresstedCandidats->setStatus('En attente');
                }
                $currentDateTime = new \DateTime();
                $intresstedCandidats->setDate($currentDateTime);
                $em->persist($intresstedCandidats);
            } elseif ($intressted === '0' && $intresstedCandidats) {
                $employeur->removeIntresstedCandidat($intresstedCandidats);
                $intressted='0';
            }

            $em->flush();
        }

        return $this->render('candidat_info/candidat.html.twig', [
            'candidat' => $candidat,
            'intressted' => $intressted,
            'canSeeCandidatPrivateInfo' => $canSeeCandidatPrivateInfo,
        ]);
    }

}
