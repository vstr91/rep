<?php

namespace Rep\SiteBundle\Entity\Repository;

/**
 * ComentarioEventoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComentarioEventoRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('ce')
                ->select('ce.id, ce.texto, e.id AS evento, ce.status, ce.ultimaAlteracao AS ultima_alteracao')
                ->distinct()
                ->leftJoin("RepSiteBundle:Evento", "e", "WITH", "e.id = ce.evento")
                ->where("ce.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('ce.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
}
