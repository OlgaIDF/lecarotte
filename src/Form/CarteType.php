<?php

namespace App\Form;

use App\Entity\Carte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('categorie', TextType::class)
            ->add('ingredients', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('img', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Image'
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'Valider'
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);
    }
}
