<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                'attr' => ['placeholder' => 'Nom de votre sortie'],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
//                'widget' => 'single_text',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
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
                'attr' => ['placeholder' => 'Nombre de places'],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée (min): ',
                'trim' => true,
                'required' => true,
                'attr' => ['placeholder' => 'Durée en minutes'],
            ])
            ->add('outingInfo', TextareaType::class, [
                'label' => 'Description et infos',
                'attr' => ['placeholder' => 'Description et infos'],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus : ',
                'choice_label' => 'name'
            ])
            ->add('place', null, [
                'mapped' => false,
                'attr' => ['type' => 'hidden']
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