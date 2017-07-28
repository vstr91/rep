<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MusicaRepertorioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusicaRepertorioRepository extends EntityRepository
{
    
    function listaMusicasAtivasAusentesNoRepertorio($id_repertorio, $id_projeto){
//        $qb = $this->createQueryBuilder('me');
//        
//        $musicasRepertorio = $qb->select('IDENTITY(me.musica)')
//          ->leftJoin("RepSiteBundle:Repertorio", 'e', 'WITH', 'e.id = me.repertorio')
//          ->where("me.repertorio = '".$id_repertorio."'")
//          ->andWhere('me.status = 0')
//          //->andWhere("e.projeto = :projeto")
//          //      ->setParameter('projeto', "'".$id_projeto."'")
//          ->getQuery()
//          ->getResult();
//        
//        if(sizeof($musicasRepertorio) > 0){
//            $result = $qb->select('m1')
//            ->distinct()
//            ->from("RepSiteBundle:Musica", "m1")
//                    ->innerJoin('RepSiteBundle:MusicaProjeto', 'mp', 'WITH', 'mp.musica = m1.id')
//            ->where('m1.id NOT IN (:musicasRepertorio)')
//                    //->andWhere('m1.status = 0')
//                    ->andWhere('mp.projeto = :projeto')
//                    ->setParameter('musicasRepertorio', $musicasRepertorio)
//                    ->setParameter('projeto', "'".$id_projeto."'")
//                    ->addOrderBy('m1.nome')
//            ->getQuery()
//            ->getResult();
//        } else{
////            $qb = $this->createQueryBuilder('me');
////            
////            $result = $qb->select('mu')
////            ->distinct()
////            ->from("RepSiteBundle:Musica", "mu")
////                    ->where('mu.id NOT IN (0)')
////                    //->andWhere('mu.status = 0')
////                    ->addOrderBy('mu.nome')
////            ->getQuery()
////            ->getResult();
//            return null;
//        }
        
        $sql = "SELECT m.* 
                FROM musica m LEFT JOIN
                     musica_projeto mp ON mp.id_musica = m.id
                WHERE mp.id_projeto = '".$id_projeto."'
                AND  mp.status IN (0,1)
                AND  m.id NOT IN (SELECT m.id FROM musica_repertorio mr INNER JOIN 
                                                   musica m ON m.id = mr.id_musica 
                                              WHERE mr.id_repertorio = '".$id_repertorio."' AND mr.status = 0) ORDER BY m.nome";
        
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, "Rep\SiteBundle\Entity\Musica");
        
        return $result;
        
    }
    
    public function invalidaTodasMusicasRepertorio($id_repertorio){
        
        $qb = $this->createQueryBuilder('mr')
                ->update("RepSiteBundle:MusicaRepertorio", 'mr')
                ->set('mr.status', 2)
                ->set('mr.ultimaAlteracao', ':now')
                ->where('mr.repertorio = :id_repertorio')
                ->andWhere('mr.status != 2')
                ->setParameter('now', new \DateTime())
                ->setParameter('id_repertorio', $id_repertorio);

        $qb->getQuery()->getResult();
        
        return true;
        
    }
    
    public function listarTodasPorRepertorio($slug){
        $qb = $this->createQueryBuilder('mr')
                ->select('m')
                ->leftJoin("RepSiteBundle:Musica", 'm', 'WITH', 'm.id = mr.musica')
                ->leftJoin("RepSiteBundle:Repertorio", 'e', 'WITH', 'e.id = mr.repertorio')
                ->andWhere('e.slug = :repertorio')
                ->andWhere('mr.status = 0')
                ->setParameter(':repertorio', $slug)
                ->addOrderBy('mr.ordem');

        return $qb->getQuery()->getResult();
    }
    
    public function contarTodasPorRepertorio($slug){
        $qb = $this->createQueryBuilder('mr')
                ->select('count(mr.id)')
                ->leftJoin("RepSiteBundle:Musica", 'm', 'WITH', 'm.id = mr.musica')
                ->leftJoin("RepSiteBundle:Repertorio", 'r', 'WITH', 'r.id = mr.repertorio')
                ->andWhere('r.slug = :repertorio')
                ->andWhere('mr.status = 0')
                ->setParameter(':repertorio', $slug)
                ->addOrderBy('mr.ordem');

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('mr')
                ->select('mr.id', 'mr.observacao', 'm.id AS musica', 'r.id AS repertorio', 'mr.status', 
                        'mr.ultimaAlteracao AS ultima_alteracao', 'mr.ordem')
                ->distinct()
                ->leftJoin("RepSiteBundle:Musica", "m", "WITH", "m.id = mr.musica")
                ->leftJoin("RepSiteBundle:Repertorio", "r", "WITH", "e.id = mr.repertorio")
                ->where("mr.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('mr.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
    function listarRepertoriosMusica($musica, $tipoRepertorio = null){
        
        if($tipoRepertorio){
            $sql = "SELECT mr.* 
                FROM musica_repertorio mr INNER JOIN
                          musica m ON m.id = mr.id_musica INNER JOIN
                          repertorio r ON r.id = mr.id_repertorio
                WHERE m.id = '".$musica."'
                ORDER BY r.`nome` DESC";
        } else{
            $sql = "SELECT mr.* 
                FROM musica_repertorio mr INNER JOIN
                          musica m ON m.id = mr.id_musica INNER JOIN
                          repertorio r ON r.id = mr.id_repertorio
                WHERE m.id = '".$musica."'
                ORDER BY r.`nome` DESC";
        }
        
        
        
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, "Rep\SiteBundle\Entity\MusicaRepertorio");
        
        return $result;
        
    }
    
}
