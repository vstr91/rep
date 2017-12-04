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
 * Description of ContatoCasa
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\ContatoCasaRepository")
 * @ORM\Table(name="contato_casa")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class ContatoCasa extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="observacao", type="string", length=100, nullable=true)
     * 
     */
    protected $observacao;
    
    /**
     * @ORM\Column(name="cargo", type="string", length=100, nullable=true)
     * @Gedmo\Versioned
     * 
     */
    protected $cargo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Contato")
     * @ORM\JoinColumn(name="id_contato", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $contato;
    
    /**
     * @ORM\ManyToOne(targetEntity="Casa", inversedBy="contatosCasa")
     * @ORM\JoinColumn(name="id_casa", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $casa;
    

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

    /**
     * Set ordem
     *
     * @param integer $ordem
     *
     * @return MusicaEvento
     */
    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;

        return $this;
    }

    /**
     * Get ordem
     *
     * @return integer
     */
    public function getOrdem()
    {
        return $this->ordem;
    }

    /**
     * Set usuarioCadastro
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioCadastro
     *
     * @return MusicaEvento
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
     * @return MusicaEvento
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
     * Set cargo
     *
     * @param string $cargo
     *
     * @return ContatoCasa
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set contato
     *
     * @param \Rep\SiteBundle\Entity\Contato $contato
     *
     * @return ContatoCasa
     */
    public function setContato(\Rep\SiteBundle\Entity\Contato $contato = null)
    {
        $this->contato = $contato;

        return $this;
    }

    /**
     * Get contato
     *
     * @return \Rep\SiteBundle\Entity\Contato
     */
    public function getContato()
    {
        return $this->contato;
    }

    /**
     * Set casa
     *
     * @param \Rep\SiteBundle\Entity\Casa $casa
     *
     * @return ContatoCasa
     */
    public function setCasa(\Rep\SiteBundle\Entity\Casa $casa = null)
    {
        $this->casa = $casa;

        return $this;
    }

    /**
     * Get casa
     *
     * @return \Rep\SiteBundle\Entity\Casa
     */
    public function getCasa()
    {
        return $this->casa;
    }
}
