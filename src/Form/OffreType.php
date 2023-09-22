<?php

namespace App\Form;

use App\Entity\CandidatInfo;
use App\Entity\Category;
use App\Entity\Offre;
use App\Entity\Secteur;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', TextareaType::class)
            ->add('autreExigences', TextareaType::class)
            ->add('autreAvantages', TextareaType::class)
            ->add('demarrage', DateType::class, [
                'label' => 'Démarrage',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'format' => 'dd-MM-yyyy',
            ])
            ->add('profil', ChoiceType::class, [
                'label' => 'Profil recherché',
                'choices' => [
                    'Junior' => CandidatInfo::JUNIOR,
                    'Confirmé' => CandidatInfo::CONFIRME,
                    'Senior' => CandidatInfo::SENIOR,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('tjm', NumberType::class, [
                'label' => 'TJM',
                'required' => false,
            ])
            ->add('lieu')
            ->add('skills', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'class' => Secteur::class,
                'choice_label' => 'titre',
                'multiple' => false,
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'titre',
                'multiple' => false,
                'required' => false,
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => 'Type de contrat',
                'choices' => [
                    'CDI' => CandidatInfo::CONTRAT_CDI,
                    'Freelance' => CandidatInfo::CONTRAT_FREELANCE,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('salaire', NumberType::class, [
                'label' => 'Salaire',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
