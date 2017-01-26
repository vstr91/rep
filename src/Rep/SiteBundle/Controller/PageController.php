<?php

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Form\ArtistaType;
use Rep\SiteBundle\Form\EventoType;
use Rep\SiteBundle\Form\MusicaType;
use Rep\SiteBundle\Form\ProjetoType;
use Rep\SiteBundle\Form\TipoEventoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $artistas = $em->getRepository('RepSiteBundle:Artista')->listarTodosPorQuantidadeMusicas();
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')->listarTodasPorSituacao();
        
        $execucoes = $em->getRepository('RepSiteBundle:Musica')->listarTodasPorDataExecucao();
        
        $musicasAtivas = $em->getRepository('RepSiteBundle:Musica')
                ->findBy(array('status' => 0), array('nome' => 'ASC'));
        $musicasEmEspera = $em->getRepository('RepSiteBundle:Musica')
                ->findBy(array('status' => 1), array('nome' => 'ASC'));
        $musicasSugestao = $em->getRepository('RepSiteBundle:Musica')
                ->findBy(array('status' => 3), array('nome' => 'ASC'));
        
        $proximosEventos = $em->getRepository('RepSiteBundle:Evento')
                ->listarProximosEventos();
        
        return $this->render('RepSiteBundle:Page:index.html.twig', array(
            'musicasAtivas' => $musicasAtivas,
            'musicasEmEspera' => $musicasEmEspera,
            'musicasSugestao' => $musicasSugestao,
            'proximosEventos' => $proximosEventos,
            'artistas' => $artistas,
            'musicas' => $musicas,
            'execucoes' => $execucoes
        ));
    }
    
    public function artistasAction()
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $artistaType = new ArtistaType();
        $formArtista = $this->createForm($artistaType);
        
        $artistas = $em->getRepository('RepSiteBundle:Artista')
                ->listarTodos();
        
        return $this->render('RepSiteBundle:Page:artistas.html.twig', array(
            'usuario' => $user,
            'artistas' => $artistas,
            'formArtista' => $formArtista->createView()
        ));
    }
    
    public function musicasAction()
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $musicaType = new MusicaType();
        $formMusica = $this->createForm($musicaType);
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')
                ->listarTodas();
        
        return $this->render('RepSiteBundle:Page:musicas.html.twig', array(
            'usuario' => $user,
            'musicas' => $musicas,
            'formMusica' => $formMusica->createView()
        ));
    }
    
    public function eventosAction()
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $eventoType = new EventoType();
        $formEvento = $this->createForm($eventoType);
        
        $eventos = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodos();
        
        $tiposEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                ->findAll();
        
        $eventosAtivos = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodosAtivos();
        
        return $this->render('RepSiteBundle:Page:eventos.html.twig', array(
            'usuario' => $user,
            'eventos' => $eventos,
            'eventosAtivos' => $eventosAtivos,
            'tiposEvento' => $tiposEvento,
            'formEvento' => $formEvento->createView()
        ));
    }
    
    public function tiposEventoAction()
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $tipoEventoType = new TipoEventoType();
        $formTipoEvento = $this->createForm($tipoEventoType);
        
        $tiposEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:tipos-evento.html.twig', array(
            'usuario' => $user,
            'tiposEvento' => $tiposEvento,
            'formTipoEvento' => $formTipoEvento->createView()
        ));
    }
    
    public function projetosAction()
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $projetoType = new ProjetoType();
        $formProjeto = $this->createForm($projetoType);
        
        $projetos = $em->getRepository('RepSiteBundle:Projeto')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:projetos.html.twig', array(
            'usuario' => $user,
            'projetos' => $projetos,
            'formProjeto' => $formProjeto->createView()
        ));
    }
    
}
