<?php

namespace Rep\SiteBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
//            ->add('status', 'choice', array(
//                'choices' => array('0' => 'Ativo', '1' => 'Em Espera', '2' => 'Inativo', 
//                    '3' => 'Sugestão')
//            ))
            ->add('artista', null, array(
                'query_builder' => function(EntityRepository $repository) { 
                    return $repository->createQueryBuilder('a')->orderBy('a.nome', 'ASC');
                },
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
            'data_class' => 'Rep\SiteBundle\Entity\Musica'
        ));
    }
}
