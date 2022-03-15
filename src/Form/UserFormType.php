<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'trim' => true,
                'required' => true
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'trim' => true,
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'trim' => true,
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'trim' => true,
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'trim' => true,
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => false,
                'constraints'=> new Length(['min' => 6,'minMessage'=>'le mot de passe doit contenir au moins 6 caractères']),
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'label' => 'Campus',
                'choice_label' => 'name'
            ])
            ->add('picture', FileType::class, [
                'label' => 'Ma photo',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'L\'extension de cette image est incorrect',
                        'maxSizeMessage' => 'L\'image est trop volumineuse, veuillez sélectionner une autre image'
                    ])
                ],
                'help' => 'Fichiers .png, .jpg, .jpeg acceptés. 1024k max',
                'data_class' => null
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
