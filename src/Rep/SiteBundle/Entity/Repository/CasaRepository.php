<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CasaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CasaRepository extends EntityRepository
{
    
    public function listarTodos(){
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.nome');

        return $qb->getQuery()->getResult();
    }
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('c')
//                ->select('b.id, b.nome, b.status, l.id AS local')
                ->select('c.id, c.nome, c.status, c.ultimaAlteracao AS ultima_alteracao, c.slug')
                ->distinct()
//                ->leftJoin("CircularSiteBundle:Local", "l", "WITH", "l.id = b.local")
                ->where("c.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('c.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
}