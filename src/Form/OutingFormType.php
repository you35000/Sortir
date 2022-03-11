<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la sortie',
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début'
            ])
            ->add('limitDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de fin d\'inscription'
            ])
            ->add('nbInscription', NumberType::class, [
                'label' => 'Nombre d\'inscription possible'
            ])
            ->add('outingInfo', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée estimée de la sortie',
                'help' => 'en minutes'
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'disabled' => true,
            ])
            ->add('Place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu'
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Créer'
            ])
            ->add('published', SubmitType::class, [
                'label' => 'Publier'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
