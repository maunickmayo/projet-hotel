<?php

namespace App\Form;

use App\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SliderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ordre', TextType::class, [
            'label' => 'Ordre' 
            ])
            ->add('photo', FileType::class, [
                'label' => "Photo du produit",
                'data_class' => null,
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les formats autorisés sont .jpg ou .png',
                        'maxSize' => '3M',
                        'maxSizeMessage' => 'Le poids maximal du fichier est : {{ limit }} {{ suffix }} ({{ name }}: {{ size }} {{ suffix }})',
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregister',
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-3 btn btn-success'
                ],
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slider::class,
        ]);
    }
}
