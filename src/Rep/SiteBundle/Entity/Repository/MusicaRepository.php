<?php

namespace Rep\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MusicaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusicaRepository extends EntityRepository
{
    
    public function listarTodas(){
        $qb = $this->createQueryBuilder('m')
                ->select('m')
                ->addOrderBy('m.nome');

        return $qb->getQuery()->getResult();
    }
    
    public function listarTodasAtivas(){
        $qb = $this->createQueryBuilder('m')
                ->select('m')
                ->where('m.status = 0')
                ->addOrderBy('m.nome');

        return $qb->getQuery()->getResult();
    }
    
    public function listarTodosREST($limite = null, $dataUltimoAcesso){
        $qb = $this->createQueryBuilder('m')
                ->select('m.id', 'm.nome', 'a.id AS artista', 'm.status', 'm.ultimaAlteracao AS ultima_alteracao, m.slug, m.tom')
                ->distinct()
                ->leftJoin("RepSiteBundle:Artista", "a", "WITH", "a.id = m.artista")
                ->where("m.ultimaAlteracao > :ultimaAlteracao")
                ->setParameter('ultimaAlteracao', $dataUltimoAcesso)
                ->addOrderBy('m.id');
        
        if(false == is_null($limite)){
            $qb->setMaxResults($limite);
        }
        
        return $qb->getQuery()->getResult();
        
    }
    
    public function listarTodasPorSituacao(){
        $qb = $this->createQueryBuilder('m')
                ->select('m.status', 'COUNT(m.id) AS quantidade')
                ->groupBy('m.status')
                ;

        return $qb->getQuery()->getResult();
    }
    
    public function listarTodasPorDataExecucao(){
        
        $sql = "SELECT m.nome, a.nome AS artista,
(
	SELECT MAX(e.data)
	FROM musica_evento me INNER JOIN
		  evento e ON e.id = me.id_evento
	WHERE e.id = me.id_evento
	AND   me.id_musica = m.id 
	AND   me.`status` <> 2
	AND   e.data <= NOW()
) AS 'execucao'
FROM musica m INNER JOIN
     artista a ON a.id = m.id_artista
GROUP BY m.nome
ORDER BY execucao, m.nome";
        
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }
    
}
