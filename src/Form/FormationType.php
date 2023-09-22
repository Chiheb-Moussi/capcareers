<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', TextareaType::class)
            ->add('dateFormation')
            ->add('dateFormation', DateType::class, [
                'label' => 'Date de formation',
                'widget' => 'single_text', // Renders as a single text input
                'html5' => false, // Set to true for HTML5 date input
                'required' => false, // Set to true if this field is mandatory
                'format' => 'dd-MM-yyyy', // Specify your desired date format
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
