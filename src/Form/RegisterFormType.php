<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' =>'Pseudo'
            ])
            ->add('mdp',  PasswordType::class, [
                'label' =>'Mot de passe'
            ])

            ->add('nom', TextType::class, [
                'label' =>'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' =>'Prenom'
            ])
            ->add('email', EmailType::class, [
                'label' =>'E-mail'
            ])
            ->add('civilite', ChoiceType::class, [
                'label' => 'Sexe',
                'expanded' => true,
                'choices' => [
                    "Homme" => 'homme',
                    "Femme" => 'femme'
                ],
                'choice_attr' => [
                  "Homme" => ['selected' => true],
                ],
                
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block col-3 my-3 mx-auto btn btn-success'
                ]
            ])
    
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
