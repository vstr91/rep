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
                ->addOrderBy('e.data', 'DESC');
        
        return $qb->getQuery()->getResult();
    }
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('e')
                ->select('e.id, e.nome, e.data, e.status, te.id AS tipo_evento, e.ultimaAlteracao AS ultima_alteracao')
                ->distinct()
                ->leftJoin("RepSiteBundle:TipoEvento", "te", "WITH", "te.id = e.tipoEvento")
                ->where("e.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('e.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
}
