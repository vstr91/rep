<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of Musica
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\MusicaRepository")
 * @ORM\Table(name="musica")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity({"nome", "artista"}, message="Já existe uma música com esse título vinculada a esse artista")
 * 
 */
class Musica extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100)
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * 
     */
    protected $nome;
    
    /**
     * @ORM\ManyToOne(targetEntity="Artista", inversedBy="musicas")
     * @ORM\JoinColumn(name="id_artista", referencedColumnName="id")
     * @OrderBy({"artista" = "DESC"})
     * @Gedmo\Versioned
     * 
     */
    protected $artista;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tom", type="string", length=5, nullable=true)
     * @Gedmo\Versioned
     * 
     */
    protected $tom;
    
    /**
     * @Gedmo\Slug(fields={"nome"})
     * @ORM\Column(unique=false)
     */
    private $slug;
    
    /**
     * @ORM\ManyToOne(targetEntity="Estilo", inversedBy="musicas")
     * @ORM\JoinColumn(name="id_estilo", referencedColumnName="id")
     * @OrderBy({"estilo" = "ASC"})
     * @Gedmo\Versioned
     * 
     */
    protected $estilo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="letra", type="text", nullable=true)
     * 
     */
    protected $letra;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cifra", type="text", nullable=true)
     * 
     */
    protected $cifra;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observacoes", type="text", nullable=true)
     * 
     */
    protected $observacoes;
    
    public function __toString() {
        return $this->getNome();
    }
    

    /**
     * Set nome
     *
     * @param string $nome
     * @return Musica
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
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
     * @return Musica
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
     * @return Musica
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
     * @return Musica
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
     * Set artista
     *
     * @param Artista $artista
     * @return Musica
     */
    public function setArtista(Artista $artista = null)
    {
        $this->artista = $artista;

        return $this;
    }

    /**
     * Get artista
     *
     * @return Artista 
     */
    public function getArtista()
    {
        return $this->artista;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Musica
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set usuarioCadastro
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioCadastro
     *
     * @return Musica
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
     * @return Musica
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
     * Set tom
     *
     * @param string $tom
     *
     * @return Musica
     */
    public function setTom($tom)
    {
        $this->tom = $tom;

        return $this;
    }

    /**
     * Get tom
     *
     * @return string
     */
    public function getTom()
    {
        return $this->tom;
    }

    /**
     * Set estilo
     *
     * @param \Rep\SiteBundle\Entity\Estilo $estilo
     *
     * @return Musica
     */
    public function setEstilo(\Rep\SiteBundle\Entity\Estilo $estilo = null)
    {
        $this->estilo = $estilo;

        return $this;
    }

    /**
     * Get estilo
     *
     * @return \Rep\SiteBundle\Entity\Estilo
     */
    public function getEstilo()
    {
        return $this->estilo;
    }

    /**
     * Set letra
     *
     * @param string $letra
     *
     * @return Musica
     */
    public function setLetra($letra)
    {
        $this->letra = $letra;

        return $this;
    }

    /**
     * Get letra
     *
     * @return string
     */
    public function getLetra()
    {
        return $this->letra;
    }
    
    function getObservacoes() {
        return $this->observacoes;
    }

    function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }


    /**
     * Set cifra
     *
     * @param string $cifra
     *
     * @return Musica
     */
    public function setCifra($cifra)
    {
        $this->cifra = $cifra;

        return $this;
    }

    /**
     * Get cifra
     *
     * @return string
     */
    public function getCifra()
    {
        return $this->cifra;
    }
}
