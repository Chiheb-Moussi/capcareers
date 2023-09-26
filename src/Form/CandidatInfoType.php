<?php

namespace App\Form;

use App\Entity\CandidatInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CandidatInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profession', TextType::class, [
                'label' => 'Profession',
                'required' => true,
            ])
            ->add('disponibilite', DateType::class, [
                'label' => 'Disponibilité',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'format' => 'dd-MM-yyyy',
            ])
            ->add('tjm', NumberType::class, [
                'label' => 'TJM',
                'required' => false,
            ])
            ->add('typeProfile', ChoiceType::class, [
                'label' => 'Type profile',
                'choices' => [
                    'Junior' => CandidatInfo::JUNIOR,
                    'Confirmé' => CandidatInfo::CONFIRME,
                    'Sénior' => CandidatInfo::SENIOR,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('nombreExp', IntegerType::class, [
                'label' => 'Nombre d\'Expérience',
                'required' => false,
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => 'Type de contrat souhaité',
                'choices' => [
                    'CDI' => CandidatInfo::CONTRAT_CDI,
                    'Freelance' => CandidatInfo::CONTRAT_FREELANCE,
                    'Portage salarial' => CandidatInfo::CONTRAT_PORTAGE_SALARIAL,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('cv', FileType::class, [
                'label' => 'Mon CV',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CandidatInfo::class,
        ]);
    }
}
