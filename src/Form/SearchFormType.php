<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\Model\SearchOuting;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('search', null, [
                'required' => false,
                'label' => 'Rechercher une sortie'
            ])
            ->add('dateStarted', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
//                'format' => 'dd-MM-yyyy',
                'label' => 'Entre',
            ])
            ->add('dateEnded', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
//                'format' => 'dd-MM-yyyy',
                'label' => 'Et',

            ])
            ->add('isOrganizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur',
                'required' => false
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label' => 'Sorties auquelles je suis inscrit',
                'required' => false
            ])
            ->add('isNotRegistered', CheckboxType::class, [
                'label' => 'Sorties auquelles je ne suis pas inscrit',
                'required' => false
            ])
            ->add('isOver', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchOuting::class,
        ]);
    }
}
