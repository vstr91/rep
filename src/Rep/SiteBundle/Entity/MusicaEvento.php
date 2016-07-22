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

/**
 * Description of MusicaEvento
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\MusicaEventoRepository")
 * @ORM\Table(name="musica_evento")
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class MusicaEvento extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="observacao", type="string", length=100, nullable=true)
     * 
     */
    protected $observacao;
    
    /**
     * @ORM\ManyToOne(targetEntity="Musica")
     * @ORM\JoinColumn(name="id_musica", referencedColumnName="id")
     * 
     */
    protected $musica;
    
    /**
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * 
     */
    protected $evento;
    

    /**
     * Set observacao
     *
     * @param string $observacao
     * @return MusicaEvento
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * Get observacao
     *
     * @return string 
     */
    public function getObservacao()
    {
        return $this->observacao;
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
     * Set status
     *
     * @param integer $status
     * @return MusicaEvento
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
     * @return MusicaEvento
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
     * @return MusicaEvento
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
     * Set musica
     *
     * @param \Rep\SiteBundle\Entity\Musica $musica
     * @return MusicaEvento
     */
    public function setMusica(\Rep\SiteBundle\Entity\Musica $musica = null)
    {
        $this->musica = $musica;

        return $this;
    }

    /**
     * Get musica
     *
     * @return \Rep\SiteBundle\Entity\Musica 
     */
    public function getMusica()
    {
        return $this->musica;
    }

    /**
     * Set evento
     *
     * @param \Rep\SiteBundle\Entity\Evento $evento
     * @return MusicaEvento
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
