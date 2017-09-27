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
 * Description of TempoMusicaEvento
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\TempoMusicaEventoRepository")
 * @ORM\Table(name="tempo_musica_evento")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class TempoMusicaEvento extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="tempo", type="time")
     * 
     */
    protected $tempo;
    
    /**
     * @ORM\ManyToOne(targetEntity="MusicaEvento")
     * @ORM\JoinColumn(name="id_musica_evento", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $musicaEvento;
    
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
     * @return TempoMusicaEvento
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
     * @return TempoMusicaEvento
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
     * @return TempoMusicaEvento
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
     * @return TempoMusicaEvento
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
     * Set musicaEvento
     *
     * @param \Rep\SiteBundle\Entity\MusicaEvento $musicaEvento
     *
     * @return TempoMusicaEvento
     */
    public function setMusicaEvento(\Rep\SiteBundle\Entity\MusicaEvento $musicaEvento = null)
    {
        $this->musicaEvento = $musicaEvento;

        return $this;
    }

    /**
     * Get musicaEvento
     *
     * @return \Rep\SiteBundle\Entity\MusicaEvento
     */
    public function getMusicaEvento()
    {
        return $this->musicaEvento;
    }

    /**
     * Set usuarioCadastro
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioCadastro
     *
     * @return TempoMusicaEvento
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
     * @return TempoMusicaEvento
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

    /**
     * Set audio
     *
     * @param string $audio
     *
     * @return TempoMusicaEvento
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
}
