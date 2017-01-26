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
 * Description of MusicaProjeto
 *
 * @author Almir
 */

/**
 * Musica
 *
 * @ORM\Entity(repositoryClass="Rep\SiteBundle\Entity\Repository\MusicaProjetoRepository")
 * @ORM\Table(name="musica_projeto")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * 
 */
class MusicaProjeto extends EntidadeBase {
    
    /**
     * @ORM\ManyToOne(targetEntity="Musica")
     * @ORM\JoinColumn(name="id_musica", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $musica;
    
    /**
     * @ORM\ManyToOne(targetEntity="Projeto")
     * @ORM\JoinColumn(name="id_projeto", referencedColumnName="id")
     * @Gedmo\Versioned
     * 
     */
    protected $projeto;
    

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
     * @return MusicaProjeto
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
     * @return MusicaProjeto
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
     * @return MusicaProjeto
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
     *
     * @return MusicaProjeto
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
     * Set projeto
     *
     * @param \Rep\SiteBundle\Entity\Projeto $projeto
     *
     * @return MusicaProjeto
     */
    public function setProjeto(\Rep\SiteBundle\Entity\Projeto $projeto = null)
    {
        $this->projeto = $projeto;

        return $this;
    }

    /**
     * Get projeto
     *
     * @return \Rep\SiteBundle\Entity\Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }
}
