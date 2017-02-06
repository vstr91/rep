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
 * Description of Evento
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\EventoRepository")
 * @ORM\Table(name="evento")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class Evento extends EntidadeBase {
    
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
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $data;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoEvento")
     * @ORM\JoinColumn(name="id_tipo_evento", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $tipoEvento;
    
    /**
     * @Gedmo\Slug(fields={"nome", "data"})
     * @ORM\Column(unique=false)
     */
    private $slug;


    /**
     * Set nome
     *
     * @param string $nome
     * @return Evento
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
     * Set data
     *
     * @param \DateTime $data
     * @return Evento
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime 
     */
    public function getData()
    {
        return $this->data;
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
     * @return Evento
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
     * @return Evento
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
     * @return Evento
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
     * Set tipoEvento
     *
     * @param \Rep\SiteBundle\Entity\TipoEvento $tipoEvento
     * @return Evento
     */
    public function setTipoEvento(\Rep\SiteBundle\Entity\TipoEvento $tipoEvento = null)
    {
        $this->tipoEvento = $tipoEvento;

        return $this;
    }

    /**
     * Get tipoEvento
     *
     * @return \Rep\SiteBundle\Entity\TipoEvento 
     */
    public function getTipoEvento()
    {
        return $this->tipoEvento;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Evento
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
     * @return Evento
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
     * @return Evento
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
