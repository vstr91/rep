<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MusicaEventoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusicaEventoRepository extends EntityRepository
{
    
    function listaMusicasAtivasAusentesNoEvento($id_evento){
        $qb = $this->createQueryBuilder('me');
        
        $musicasEvento = $qb->select('IDENTITY(me.musica)')
          ->where("me.evento = '".$id_evento."'")
          ->andWhere('me.status = 0')
          ->getQuery()
          ->getResult();
        
        if(sizeof($musicasEvento) > 0){
            $result = $qb->select('m1')
            ->distinct()
            ->from("RepSiteBundle:Musica", "m1")
            ->where('m1.id NOT IN (:musicasEvento)')
                    //->andWhere('m1.status = 0')
                    ->setParameter('musicasEvento', $musicasEvento)
                    ->addOrderBy('m1.nome')
            ->getQuery()
            ->getResult();
        } else{
            $qb = $this->createQueryBuilder('me');
            
            $result = $qb->select('mu')
            ->distinct()
            ->from("RepSiteBundle:Musica", "mu")
                    ->where('mu.id NOT IN (0)')
                    //->andWhere('mu.status = 0')
                    ->addOrderBy('mu.nome')
            ->getQuery()
            ->getResult();
        }
         
        return $result;
        
    }
    
    public function invalidaTodasMusicasEvento($id_evento){
        
        $qb = $this->createQueryBuilder('me')
                ->update("RepSiteBundle:MusicaEvento", 'me')
                ->set('me.status', 2)
                ->set('me.ultimaAlteracao', ':now')
                ->where('me.evento = :id_evento')
                ->andWhere('me.status != 2')
                ->setParameter('now', new \DateTime())
                ->setParameter('id_evento', $id_evento);

        $qb->getQuery()->getResult();
        
        return true;
        
    }
    
    public function listarTodasPorEvento($slug){
        $qb = $this->createQueryBuilder('me')
                ->select('m')
                ->leftJoin("RepSiteBundle:Musica", 'm', 'WITH', 'm.id = me.musica')
                ->leftJoin("RepSiteBundle:Evento", 'e', 'WITH', 'e.id = me.evento')
                ->andWhere('e.slug = :evento')
                ->andWhere('me.status = 0')
                ->setParameter(':evento', $slug)
                ->addOrderBy('me.ordem');

        return $qb->getQuery()->getResult();
    }
    
    public function contarTodasPorEvento($slug){
        $qb = $this->createQueryBuilder('me')
                ->select('count(me.id)')
                ->leftJoin("RepSiteBundle:Musica", 'm', 'WITH', 'm.id = me.musica')
                ->leftJoin("RepSiteBundle:Evento", 'e', 'WITH', 'e.id = me.evento')
                ->andWhere('e.slug = :evento')
                ->andWhere('me.status = 0')
                ->setParameter(':evento', $slug)
                ->addOrderBy('me.ordem');

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('me')
                ->select('me.id', 'me.observacao', 'm.id AS musica', 'e.id AS evento', 'me.status', 
                        'me.ultimaAlteracao AS ultima_alteracao', 'me.ordem')
                ->distinct()
                ->leftJoin("RepSiteBundle:Musica", "m", "WITH", "m.id = me.musica")
                ->leftJoin("RepSiteBundle:Evento", "e", "WITH", "e.id = me.evento")
                ->where("me.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('me.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
}
