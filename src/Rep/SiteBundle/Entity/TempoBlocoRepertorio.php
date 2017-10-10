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
 * Description of TempoBlocoRepertorio
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\TempoBlocoRepertorioRepository")
 * @ORM\Table(name="tempo_bloco_repertorio")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class TempoBlocoRepertorio extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="tempo", type="time")
     * 
     */
    protected $tempo;
    
    /**
     * @ORM\ManyToOne(targetEntity="BlocoRepertorio")
     * @ORM\JoinColumn(name="id_bloco_repertorio", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $blocoRepertorio;
    
    /**
     * @var string
     *
     * @ORM\Column(name="audio", type="string", length=100, nullable=true)
     * 
     */
    protected $audio;


    /**
     * Set tempo
     *
     * @param \DateTime $tempo
     *
     * @return TempoBlocoRepertorio
     */
    public function setTempo($tempo)
    {
        $this->tempo = $tempo;

        return $this;
    }

    /**
     * Get tempo
     *
     * @return \DateTime
     */
    public function getTempo()
    {
        return $this->tempo;
    }

    /**
     * Set audio
     *
     * @param string $audio
     *
     * @return TempoBlocoRepertorio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;

        return $this;
    }

    /**
     * Get audio
     *
     * @return string
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @return string
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
     * @return TempoBlocoRepertorio
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
     * @return TempoBlocoRepertorio
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
     * @return TempoBlocoRepertorio
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
     * Set blocoRepertorio
     *
     * @param \Rep\SiteBundle\Entity\BlocoRepertorio $blocoRepertorio
     *
     * @return TempoBlocoRepertorio
     */
    public function setBlocoRepertorio(\Rep\SiteBundle\Entity\BlocoRepertorio $blocoRepertorio = null)
    {
        $this->blocoRepertorio = $blocoRepertorio;

        return $this;
    }

    /**
     * Get blocoRepertorio
     *
     * @return \Rep\SiteBundle\Entity\BlocoRepertorio
     */
    public function getBlocoRepertorio()
    {
        return $this->blocoRepertorio;
    }

    /**
     * Set usuarioCadastro
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioCadastro
     *
     * @return TempoBlocoRepertorio
     */
    public function setUsuarioCadastro(\Rep\SiteBundle\Entity\Usuario $usuarioCadastro = null)
    {
        $this->usuarioCadastro = $usuarioCadastro;

        return $this;
    }

    /**
     * Get usuarioCadastro
     *
     * @return \Rep\SiteBundle\Entity\Usuario
     */
    public function getUsuarioCadastro()
    {
        return $this->usuarioCadastro;
    }

    /**
     * Set usuarioUltimaAlteracao
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioUltimaAlteracao
     *
     * @return TempoBlocoRepertorio
     */
    public function setUsuarioUltimaAlteracao(\Rep\SiteBundle\Entity\Usuario $usuarioUltimaAlteracao = null)
    {
        $this->usuarioUltimaAlteracao = $usuarioUltimaAlteracao;

        return $this;
    }

    /**
     * Get usuarioUltimaAlteracao
     *
     * @return \Rep\SiteBundle\Entity\Usuario
     */
    public function getUsuarioUltimaAlteracao()
    {
        return $this->usuarioUltimaAlteracao;
    }
}
