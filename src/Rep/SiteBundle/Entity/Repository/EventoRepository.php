<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventoRepository extends EntityRepository
{
    
    function listarProximosEventos(){
        $qb = $this->createQueryBuilder('e')
                ->select('e')
                ->distinct()
                ->where("e.data >= :dataAtual")
                ->setParameter('dataAtual', new \DateTime())
                ->addOrderBy('e.data');
        
        return $qb->getQuery()->getResult();
    }
    
    function listarTodos(){
        $qb = $this->createQueryBuilder('e')
                ->select('e')
                ->distinct()
                ->addOrderBy('e.data');
        
        return $qb->getQuery()->getResult();
    }
    
}
