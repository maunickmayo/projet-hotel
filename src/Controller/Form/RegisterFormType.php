<?php

namespace App\Form;

use App\Entity\Membre;
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
            ->add('email', EmailType::class, [
                'label' =>'E-mail'
            ])
            ->add('password', PasswordType::class, [
                'label' =>'Mot de passe'
            ])
            ->add('pseudo',TextType::class, [
                'label' =>'Pseudo'
            ] )
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' =>'Prenom'
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
            'data_class' => Membre::class,
        ]);
    }
}
