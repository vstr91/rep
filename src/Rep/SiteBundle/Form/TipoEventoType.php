<?php

namespace Rep\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoEventoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('cor', null, array(
                'label' => 'Cor',
                'attr' => array(
                    'class' => 'color'
                )
            ))
            ->add('status', 'choice', array(
                    'choices' => array('0' => 'Ativo', '1' => 'Em Espera', '2' => 'Inativo')
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rep\SiteBundle\Entity\TipoEvento'
        ));
    }
}
