<?php

namespace App\Form;

use App\Entity\Campus;
use App\Form\Model\SearchOuting;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', Campus::class)
            ->add('search', TextType::class)
            ->add('dateStarted', \DateTime::class)
            ->add('dateEnded', \DateTime::class)
            ->add('isOrganized', CheckboxType::class)
            ->add('isRegistered', CheckboxType::class)
            ->add('isNotRegistered', CheckboxType::class)
            ->add('isOver', CheckboxType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchOuting::class,
        ]);
    }
}
