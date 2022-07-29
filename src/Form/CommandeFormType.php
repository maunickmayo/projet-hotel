<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateArrivee', DateType::class, [
                'label' => 'date_arrivée', 
                'widget' => 'single_text'
            ])
            ->add('dateDepart', DateType::class, [
                'label' => 'date_départ',
                'widget' => 'single_text'
            ])
            ->add('prixTotal', TextType::class, [
                'label' => 'prix',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix €'
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom'
            ])
            ->add('telephone', NumberType::class, [
                    'label' => 'Telephone'
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail'
            ])
                
            //->add('chambre', ChoiceType::class, [
                //'label' => 'Veuillez choisir votre type de chambre'
           // ])
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
            'data_class' => Commande::class,
        ]);
    }
}
