<?php

namespace ALgsbBundle\Form\ConnexionForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConnexionFormType extends AbstractType
{
    
    // Définition du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('login',  TextType::class,        array(  'label'=>'Login : ') )
                ->add('passwd', PasswordType::class,    array(  'label'=>'Mot de passe : ') )
                ->add('role',   ChoiceType::class,      array(  'label'=>'Rôle : ',
                                                                'choices'=>array(
                                                                    'Comptable'=>'comptable',
                                                                    'Visiteur'=>'visiteur',
                                                                )
                                                        )
                )
                ->add('submit', SubmitType::class,      array('label'=>'Valider') )
        ;
    }
    
    // Utilisation de la classe
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ALgsbBundle\Form\ConnexionForm\ConnexionFormClass'
        ));
    }
    
    public function getBlockPrefix()
    {
        return 'algsbbundle_connexionclass';
    }
}