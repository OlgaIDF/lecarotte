<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('prenom', TextType::class)
                        ->add('email', EmailType::class)
           
            ->add('message', TextareaType::class, [
                'attr' => [
                   'placeholder'=>"Comment pouvons-nous aider?" 
                ]
                
            ])
            
            ->add('RGPD', CheckboxType::class, [
               
                
                        'label' => " En cochant cette case et en soumettant ce
                        formulaire, j'accepte que mes données personnelles soient utilisées pour me recontacter dans le cadre de ma
                        demande indiquée dans ce formulaire. Aucun autre traitement ne sera effectué avec mes informations.",
                    ])
                
            
           ->add('envoyer', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-sm text-center mt-2 float-right'],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}