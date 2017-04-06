<?php

namespace Rep\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('data', 'datetime', array(
                'widget' => "single_text",
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'attr' => array(
                    'class' => 'datetimepicker'
                )
            ))
            ->add('status', 'choice', array(
                'choices' => array('0' => 'Ativo', '2' => 'Inativo')
                ))
            ->add('tipoEvento', null, array(
                'empty_value' => false
            ))
            ->add('projeto', null, array(
                'empty_value' => false
            ))
        ;
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rep\SiteBundle\Entity\Evento'
        ));
    }
}
