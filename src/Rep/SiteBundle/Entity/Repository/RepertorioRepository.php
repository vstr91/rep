<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RepertorioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RepertorioRepository extends EntityRepository
{
    
    function listarTodos(){
        $qb = $this->createQueryBuilder('r')
                ->select('r')
                ->distinct()
                ->addOrderBy('r.nome', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
    
    function listarTodosAtivos(){
        $qb = $this->createQueryBuilder('r')
                ->select('r')
                ->distinct()
                ->where('r.status != 2')
                ->addOrderBy('r.nome');
        
        return $qb->getQuery()->getResult();
    }
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('r')
                ->select('r.id, r.nome, r.status, r.ultimaAlteracao AS ultima_alteracao, r.slug, p.id AS projeto')
                ->distinct()
                ->leftJoin("RepSiteBundle:Projeto", "p", "WITH", "p.id = r.projeto")
                ->where("r.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('r.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
    function listarTodosPorProjeto($id_projeto, $limite = null){
        
        $qb = $this->createQueryBuilder('r')
                ->select('r')
                ->distinct()
                ->where("r.projeto = :projeto")
                ->andWhere('r.status = 0')
                ->addOrderBy('r.nome', 'ASC')
                ->setParameter('projeto', $id_projeto)
                ;
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
    }
    
}
