<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\CandidatInfo;
use App\Entity\Experience;
use App\Entity\Skill;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/experience')]
class ExperienceController extends AbstractController
{
    #[Route('/', name: 'app_experience_index', methods: ['GET'])]
    public function index(ExperienceRepository $experienceRepository): Response
    {
        $candidat = $this->getUser();
        /** @var CandidatInfo $candidatInfo */
        $candidatInfo = $candidat->getCandidatInfo();
        return $this->render('experience/index.html.twig', [
            'experiences' => $candidatInfo->getExperiences(),
            'left_menu'=> 'experience',
        ]);
    }

    #[Route('/new', name: 'app_experience_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SkillRepository $skillRepository): Response
    {
        /** @var Candidat $candidat */
        $candidat = $this->getUser();
        /** @var CandidatInfo $candidatInfo */
        $candidatInfo = $candidat->getCandidatInfo();
        $experience = new Experience();
        $experience->setCandidatInfo($candidatInfo);
        $skills = $skillRepository->findAll();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(array_key_exists('skills', $request->request->all())) {
                $skillsData = $request->request->all()['skills'];
                $experience->removeAllSkills();
                foreach ($skillsData as $skillId) {
                    $skill = $skillRepository->find($skillId);
                    if(!$skill) {
                        $skill = new Skill();
                        $skill->setTitre($skillId);
                        $entityManager->persist($skill);
                    }
                    $experience->addSkill($skill);

                }
            }
            $entityManager->persist($experience);
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('experience/new.html.twig', [
            'experience' => $experience,
            'skills' => $skills,
            'form' => $form,
            'left_menu'=> 'experience',
        ]);
    }

    #[Route('/{id}', name: 'app_experience_show', methods: ['GET'])]
    public function show(Experience $experience): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
            'left_menu'=> 'experience',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_experience_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Experience $experience, EntityManagerInterface $entityManager, SkillRepository $skillRepository): Response
    {
        $skills = $skillRepository->findAll();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(array_key_exists('skills', $request->request->all())) {
                $skillsData = $request->request->all()['skills'];
                $experience->removeAllSkills();
                foreach ($skillsData as $skillId) {
                    $skill = $skillRepository->find($skillId);
                    if(!$skill) {
                        $skill = new Skill();
                        $skill->setTitre($skillId);
                        $entityManager->persist($skill);
                    }
                    $experience->addSkill($skill);

                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form,
            'left_menu'=> 'experience',
            'skills' => $skills,
        ]);
    }

    #[Route('/{id}', name: 'app_experience_delete', methods: ['POST'])]
    public function delete(Request $request, Experience $experience, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
