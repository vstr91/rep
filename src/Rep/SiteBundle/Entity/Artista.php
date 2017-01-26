<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
//use JMS\Serializer\Annotation\ExclusionPolicy;
//use JMS\Serializer\Annotation\Expose;

/**
 * Description of Artista
 *
 * @author Almir
 */

/**
 * Artista
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\ArtistaRepository")
 * @ORM\Table(name="artista")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("nome", message="O artista já foi cadastrado")
 * 
 */
class Artista extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100, unique=true)
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * 
     */
    private $nome;
    
    /**
     * @ORM\OneToMany(targetEntity="Musica", mappedBy="artista")
     */
    private $musicas;
    
    /**
     * @Gedmo\Slug(fields={"nome"})
     * @ORM\Column(unique=false)
     */
    private $slug;
    
    public function __construct() {
        $this->musicas = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function __toString() {
        return $this->getNome();
    }
    

    /**
     * Set nome
     *
     * @param string $nome
     * @return Artista
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
     * @return Artista
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
     * @return Artista
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
     * @return Artista
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
     * Add musica
     *
     * @param \Rep\SiteBundle\Entity\Musica $musica
     *
     * @return Artista
     */
    public function addMusica(\Rep\SiteBundle\Entity\Musica $musica)
    {
        $this->musicas[] = $musica;

        return $this;
    }

    /**
     * Remove musica
     *
     * @param \Rep\SiteBundle\Entity\Musica $musica
     */
    public function removeMusica(\Rep\SiteBundle\Entity\Musica $musica)
    {
        $this->musicas->removeElement($musica);
    }

    /**
     * Get musicas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMusicas()
    {
        return $this->musicas;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Artista
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
}
