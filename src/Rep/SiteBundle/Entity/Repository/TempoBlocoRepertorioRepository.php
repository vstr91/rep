<?php

namespace Rep\SiteBundle\Entity\Repository;

/**
 * TempoBlocoRepertorioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TempoBlocoRepertorioRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('tbr')
                ->select('tbr.id', 'tbr.tempo', 'br.id AS bloco_repertorio', 'tbr.status', 'tbr.audio',
                        'tbr.ultimaAlteracao AS ultima_alteracao')
                ->distinct()
                ->leftJoin("RepSiteBundle:BlocoRepertorio", "br", "WITH", "br.id = tbr.blocoRepertorio")
                ->where("tbr.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('tbr.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
}
