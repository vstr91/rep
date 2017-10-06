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
 * Description of BlocoRepertorio
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\BlocoRepertorioRepository")
 * @ORM\Table(name="bloco_repertorio")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class BlocoRepertorio extends EntidadeBase {
    
    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100)
     * 
     */
    protected $nome;
    
    /**
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     * 
     */
    protected $ordem;
    
    /**
     * @ORM\ManyToOne(targetEntity="Repertorio", inversedBy="blocosRepertorio")
     * @ORM\JoinColumn(name="id_repertorio", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $repertorio;
    

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return BlocoRepertorio
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
     * Set ordem
     *
     * @param integer $ordem
     *
     * @return BlocoRepertorio
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
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return BlocoRepertorio
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
     * @return BlocoRepertorio
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
     * @return BlocoRepertorio
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
     * Set repertorio
     *
     * @param \Rep\SiteBundle\Entity\Repertorio $repertorio
     *
     * @return BlocoRepertorio
     */
    public function setRepertorio(\Rep\SiteBundle\Entity\Repertorio $repertorio = null)
    {
        $this->repertorio = $repertorio;

        return $this;
    }

    /**
     * Get repertorio
     *
     * @return \Rep\SiteBundle\Entity\Repertorio
     */
    public function getRepertorio()
    {
        return $this->repertorio;
    }

    /**
     * Set usuarioCadastro
     *
     * @param \Rep\SiteBundle\Entity\Usuario $usuarioCadastro
     *
     * @return BlocoRepertorio
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
     * @return BlocoRepertorio
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
