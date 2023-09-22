<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\CandidatInfo;
use App\Entity\Employeur;
use App\Repository\CandidatRepository;
use App\Repository\CategoryRepository;
use App\Repository\OffreRepository;
use App\Repository\SecteurRepository;
use App\Repository\SkillRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    public function __construct(
        private readonly OffreRepository $offreRepository,
        private readonly SkillRepository $skillRepository,
        private readonly SecteurRepository $secteurRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly CandidatRepository $candidatRepository,
    ) {}

    #[Route('/search', name: 'app_search')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $favourite = $request->query->get('favourite', '');

        $secteur = $request->query->get('secteur', '');
        $profil = $request->query->get('profil', '');
        $category = $request->query->get('category', '');
        $typeContrat = $request->query->get('typeContrat', '');
        $search = $request->query->get('search', '');
        $minTjm = $request->query->get('minTjm', '');
        $maxTjm = $request->query->get('maxTjm', '');
        $minSalaire = $request->query->get('minSalaire', '');
        $maxSalaire = $request->query->get('maxSalaire', '');
        $skillsIds = $request->query->all('skills', []);
        $offresQuery = $this->offreRepository->createQueryBuilder('o');
        if($search) {
            $offresQuery->where('o.titre like :search')
                ->setParameter('search', '%'.$search.'%');
        }
        if($secteur) {
            $offresQuery->where('o.secteur = :secteur')
                ->setParameter('secteur', $secteur);
        }
        if($category) {
            $offresQuery->where('o.category = :category')
                ->setParameter('category', $category);
        }
        if($profil) {
            $offresQuery->where('o.profil = :profil')
                ->setParameter('profil', $profil);
        }
        if($favourite) {
            $candidat = $this->getUser();
            if($candidat instanceof Candidat) {
                $offresQuery->leftJoin('o.intresstedOffres', 'io')
                    ->andWhere('io.candidat = :candidat')
                    ->setParameter('candidat', $candidat->getId());
            }
        }

        $allOffres = $offresQuery->getQuery();
        $offres = $paginator->paginate(
            $allOffres,
            $request->query->getInt('page', 1),
            6
        );



        $skills = $this->skillRepository->findAll();
        $profiles = [
            CandidatInfo::JUNIOR,
            CandidatInfo::CONFIRME,
            CandidatInfo::SENIOR,
        ];
        $typeContrats = [
            CandidatInfo::CONTRAT_CDI,
            CandidatInfo::CONTRAT_FREELANCE,
        ];
        $secteurs = $this->secteurRepository->findAll();
        $categories = $this->categoryRepository->findAll();
        return $this->render('search/index.html.twig', [
            'offres' => $offres,
            'skills' => $skills,
            'profiles' => $profiles,
            'profil' => $profil,
            'secteurs' => $secteurs,
            'secteur' => $secteur,
            'category' => $category,
            'categories' => $categories,
            'search' => $search,
            'minTjm' => $minTjm,
            'maxTjm' => $maxTjm,
            'minSalaire' => $minSalaire,
            'maxSalaire' => $maxSalaire,
            'typeContrat' => $typeContrat,
            'typeContrats' => $typeContrats,
        ]);
    }

    #[Route('/candidats/search', name: 'app_search_candidats')]
    public function searchCandidats(Request $request, PaginatorInterface $paginator): Response
    {
        $favourite = $request->query->get('favourite', '');
        $disponibilite = $request->query->get('disponibilite', '');
        $profil = $request->query->get('profil', '');
        $typeContrat = $request->query->get('typeContrat', '');
        $nombreExp = $request->query->get('nombreExp', '');
        $minTjm = $request->query->get('minTjm', '');
        $maxTjm = $request->query->get('maxTjm', '');
        $minSalaire = $request->query->get('minSalaire', '');
        $maxSalaire = $request->query->get('maxSalaire', '');
        $skillsIds = $request->query->all('skills', []);
        $candidatsQuery = $this->candidatRepository->createQueryBuilder('o');

//        if($disponibilite) {
//            $candidatsQuery->where('o.secteur = :secteur')
//                ->setParameter('secteur', $secteur);
//        }
//        if($category) {
//            $candidatsQuery->where('o.category = :category')
//                ->setParameter('category', $category);
//        }
//        if($profil) {
//            $candidatsQuery->where('o.profil = :profil')
//                ->setParameter('profil', $profil);
//        }
        if($favourite) {
            $employeur = $this->getUser();
            if($employeur instanceof Employeur) {
                $candidatsQuery->leftJoin('o.intresstedCandidats', 'io')
                    ->andWhere('io.employeur = :employeur')
                    ->setParameter('employeur', $employeur->getId());
            }
        }

        $allCandidats = $candidatsQuery->getQuery();
        $candidats = $paginator->paginate(
            $allCandidats,
            $request->query->getInt('page', 1),
            6
        );


        $skills = $this->skillRepository->findAll();
        $profiles = [
            CandidatInfo::JUNIOR,
            CandidatInfo::CONFIRME,
            CandidatInfo::SENIOR,
        ];
        $typeContrats = [
            CandidatInfo::CONTRAT_CDI,
            CandidatInfo::CONTRAT_FREELANCE,
        ];
        return $this->render('search/search-candidats.html.twig', [
            'candidats' => $candidats,
            'skills' => $skills,
            'profiles' => $profiles,
            'profil' => $profil,
            'disponibilite' => $disponibilite,
            'minTjm' => $minTjm,
            'maxTjm' => $maxTjm,
            'minSalaire' => $minSalaire,
            'maxSalaire' => $maxSalaire,
            'typeContrat' => $typeContrat,
            'typeContrats' => $typeContrats,
        ]);
    }

}
