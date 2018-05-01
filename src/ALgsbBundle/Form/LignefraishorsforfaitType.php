<?php

namespace ALgsbBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LignefraishorsforfaitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('date',       DateType::class,    array('label'=>'Date'))
                ->add('libelle',    TextType::class,    array('label'=>'Libellé'))
                ->add('montant',    TextType::class,    array('label'=>'Montant'))
                ->add('submit',     SubmitType::class,  array('label'=>'Valider'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ALgsbBundle\Entity\Lignefraishorsforfait'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'algsbbundle_lignefraishorsforfait';
    }


}
