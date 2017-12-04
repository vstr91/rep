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
 * Description of Casa
 *
 * @author Almir
 */

/**
 * Casa
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\CasaRepository")
 * @ORM\Table(name="casa")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("nome", message="A casa já foi cadastrada")
 * 
 */
class Casa extends EntidadeBase {
    
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
     * @ORM\OneToMany(targetEntity="Evento", mappedBy="casa")
     */
    private $eventos;
    
    /**
     * @Gedmo\Slug(fields={"nome"})
     * @ORM\Column(unique=false)
     */
    private $slug;
    
    public function __construct() {
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function __toString() {
        return $this->getNome();
    }
    
    
    

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Casa
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Casa
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
     * @param integer $id
     *
     * @return Casa
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
     * @return Casa
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
     * @return Casa
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
     * @return Casa
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
     * Add evento
     *
     * @param \Rep\SiteBundle\Entity\Evento $evento
     *
     * @return Casa
     */
    public function addEvento(\Rep\SiteBundle\Entity\Evento $evento)
    {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento
     *
     * @param \Rep\SiteBundle\Entity\Evento $evento
     */
    public function removeEvento(\Rep\SiteBundle\Entity\Evento $evento)
    {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos()
    {
        return $this->eventos;
    }

    /**
     * Set usuarioCadastro
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioCadastro
     *
     * @return Casa
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
     * @return Casa
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
