<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TipoEventoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TipoEventoRepository extends EntityRepository
{
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('te')
                ->select('te.id, te.nome, te.status, te.ultimaAlteracao AS ultima_alteracao')
                ->distinct()
                ->where("te.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('te.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
}
