<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of ComentarioEvento
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\ComentarioEventoRepository")
 * @ORM\Table(name="comentario_evento")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class ComentarioEvento extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     * 
     */
    protected $texto;
    
    /**
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * 
     */
    protected $evento;

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return ComentarioEvento
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return ComentarioEvento
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dataCadastro
     *
     * @param \DateTime $dataCadastro
     *
     * @return ComentarioEvento
     */
    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $dataCadastro;

        return $this;
    }

    /**
     * Get dataCadastro
     *
     * @return \DateTime
     */
    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    /**
     * Set ultimaAlteracao
     *
     * @param \DateTime $ultimaAlteracao
     *
     * @return ComentarioEvento
     */
    public function setUltimaAlteracao($ultimaAlteracao)
    {
        $this->ultimaAlteracao = $ultimaAlteracao;

        return $this;
    }

    /**
     * Get ultimaAlteracao
     *
     * @return \DateTime
     */
    public function getUltimaAlteracao()
    {
        return $this->ultimaAlteracao;
    }

    /**
     * Set evento
     *
     * @param \Rep\SiteBundle\Entity\Evento $evento
     *
     * @return ComentarioEvento
     */
    public function setEvento(\Rep\SiteBundle\Entity\Evento $evento = null)
    {
        $this->evento = $evento;

        return $this;
    }

    /**
     * Get evento
     *
     * @return \Rep\SiteBundle\Entity\Evento
     */
    public function getEvento()
    {
        return $this->evento;
    }
}
