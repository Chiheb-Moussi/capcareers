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
        $candidatsQuery = $this->candidatRepository->createQueryBuilder('c');
        
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
                $candidatsQuery->leftJoin('c.intresstedCandidats', 'ic')
                    ->andWhere('ic.employeur = :employeur')
                    ->setParameter('employeur', $employeur->getId());
            }
        }
        $verifCandidatInfo=false;
        if($profil) {
            $candidatsQuery->leftJoin('c.candidatInfo', 'co')
                ->andWhere('co.typeProfile = :profil')
               ->setParameter('profil', $profil);
               $verifCandidatInfo=true;
        }
        if(!empty($skillsIds) && $profil){
            $candidatsQuery->leftJoin('co.candidatInfoSkills', 'cif')
            ->andWhere('cif.skill in (:skill)')
           ->setParameter('skill', $skillsIds);
        }elseif(!empty($skillsIds) && !$profil){
            $candidatsQuery->leftJoin('c.candidatInfo', 'co');
            $candidatsQuery->leftJoin('co.candidatInfoSkills', 'cif')
            ->andWhere('cif.skill in (:skill)')
           ->setParameter('skill', $skillsIds);
           $verifCandidatInfo=true;   
        }
      
        if ($typeContrat) {
            if ($verifCandidatInfo) {
                $candidatsQuery
                    ->andWhere('co.typeContrat = :typeContrat')
                    ->setParameter('typeContrat', $typeContrat);
            } else {
                $candidatsQuery
                    ->leftJoin('c.candidatInfo', 'co')
                    ->andWhere('co.typeContrat = :typeContrat')
                    ->setParameter('typeContrat', $typeContrat);
            }
        }
        if ($typeContrat && $typeContrat=='Freelance' ) {
            if($minTjm)
            {
                if ($verifCandidatInfo) {
                    $candidatsQuery
                        ->andWhere('co.tjm >= :tjmMin')
                        ->setParameter('tjm', $minTjm);
                } else {
                    $candidatsQuery
                        ->leftJoin('c.candidatInfo', 'co')
                        ->andWhere('co.tjm >= :tjmMax')
                        ->setParameter('tjm', $tjmMin);
                }
            }
           
            if($maxTjm)
            {
                if ($verifCandidatInfo) {
                    $candidatsQuery
                        ->andWhere('co.tjm <= :tjmMax')
                        ->setParameter('tjm', $maxTjm);
                } else {
                    $candidatsQuery
                        ->leftJoin('c.candidatInfo', 'co')
                        ->andWhere('co.tjm <= :tjmMax')
                        ->setParameter('tjm', $maxTjm);
                }
            }
        }else{
        if($minSalaire)
        {
            if ($verifCandidatInfo) {
                $candidatsQuery
                    ->andWhere('co.salaire >= :salaire')
                    ->setParameter('salaire', $minSalaire);
            } else {
                $candidatsQuery
                    ->leftJoin('c.candidatInfo', 'co')
                    ->andWhere('co.salaire >= :salaire')
                    ->setParameter('salaire', $minSalaire);
            }
        }
       
        if($maxSalaire)
        {
            if ($verifCandidatInfo) {
                $candidatsQuery
                    ->andWhere('co.salaire <= :salaireMin')
                    ->setParameter('salaireMin', $maxSalaire);
            } else {
                $candidatsQuery
                    ->leftJoin('c.candidatInfo', 'co')
                    ->andWhere('co.salaire <= :salaireMax')
                    ->setParameter('salaireMax', $maxSalaire);
            }
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
            'skillsIds'=>$skillsIds,
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
