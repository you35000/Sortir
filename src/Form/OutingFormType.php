<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Outing;
use App\Entity\Place;
use App\Entity\State;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
                'trim' => true,
                'required' => true,
                'attr' => ['placeholder' => 'Nom de votre sortie'],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text',
                'data' => (new \DateTime())->modify('+1 day'),
            ])
            ->add('limitDate', DateType::class, [
                'label' => 'Date limite inscription',
                'widget' => 'single_text',
                'data' => (new \DateTime())->modify('+20 hours'),
            ])
            ->add('nbInscription', IntegerType::class, [
                'label' => 'Nombre de places',
                'trim' => true,
                'required' => true,
                'attr' => ['min' => 0, 'placeholder' => 'Nombre de places'],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée (min): ',
                'trim' => true,
                'required' => true,
                'attr' => ['min' => 0, 'placeholder' => 'Durée en minutes'],
            ])
            ->add('outingInfo', TextareaType::class, [
                'label' => 'Description et infos',
                'required' => true,
                'attr' => ['placeholder' => 'Description et infos'],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus : ',
                'choice_label' => 'name'

            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'required' => true,
                'label' => 'Lieu :',
                'placeholder' => 'Choisissez un lieu',
                'choice_label' => 'name',
            ]);

        $builder->add('create', SubmitType::class, [
            'label' => 'Créer',
            'attr' => array(
                'class' => 'btn btn-outline-secondary',
            )
        ]);

        $builder->add('published', SubmitType::class, [
            'label' => 'Publier',
            'attr' => array(
                'class' => 'btn btn-outline-secondary',
            )
        ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
            'embedded' => false,
        ]);
    }
}