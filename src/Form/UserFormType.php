<?php

namespace App\Form;

use App\Entity\User;
use Faker\Provider\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo',TextType::class,[
                'label'=>'Pseudo :',
                'trim'=> true,
                'required' => true,
                ])


            ->add('firstName',TextType::class,[
                'label'=>'Prénom :',
                'trim'=> true,
                'required' => true,
                ])


            ->add('lastName',TextType::class,[
                'label'=>'Nom :',
                'trim'=> true,
                'required' => true,
                ])

            ->add('phone',TelType::class,[
                'label'=>'Téléphone :',
                'trim'=> true,
                'required' => true,
                ])

            ->add('email',EmailType::class,[
                'label'=>'Email :',
                'trim'=> true,
                'required' => true,
                ])

            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Confirmation : '],
                ])

            ->add('campus',null,['choice_label'=>'name'])

            ->add('picture', FileType::class, [
                'label' => 'Ma photo :',
                'required' => false,
                'mapped' => false,
            ]);

//        $builder->add('submit', SubmitType::class, [
//            'label' => 'Enregistrer',
//            'attr' => array(
//                'class' => 'btn btn-outline-secondary',)
//        ]);
//        $builder->add('button', ButtonType::class, [
//            'label' => 'Annuler',
//            'attr' => array(
//            'class' => 'btn btn-outline-secondary',)
//
//        ]);







    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, 'validation_groups' => ['monProfil'],
        ]);
    }
}
