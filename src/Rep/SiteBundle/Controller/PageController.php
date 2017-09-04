<?php

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Musica;
use Rep\SiteBundle\Form\ArtistaType;
use Rep\SiteBundle\Form\EstiloType;
use Rep\SiteBundle\Form\EventoType;
use Rep\SiteBundle\Form\MusicaProjetoType;
use Rep\SiteBundle\Form\MusicaType;
use Rep\SiteBundle\Form\ProjetoType;
use Rep\SiteBundle\Form\TipoEventoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Rep\SiteBundle\Controller\simple_html_dom;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $eventoType = new EventoType();
        $formEvento = $this->createForm($eventoType);
        
        $eventos = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodos();
        
        $tiposEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                ->findAll();
        
        $eventosAtivos = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodosAtivos();
        
        return $this->render('RepSiteBundle:Page:index.html.twig', array(
            'usuario' => $this->getUser(),
            'eventos' => $eventos,
            'eventosAtivos' => $eventosAtivos,
            'tiposEvento' => $tiposEvento,
            'formEvento' => $formEvento->createView()
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
                ->listarTodosExcetoPadrao();
//                ->findAll();
        
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
    
    public function musicasProjetosAction($projeto)
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $musicaProjetoType = new MusicaProjetoType();
        $formMusicaProjeto = $this->createForm($musicaProjetoType);
        
        $projeto = $em->getRepository('RepSiteBundle:Projeto')->findOneBy(array('slug' => $projeto));
        
        $ensaios = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodosPassadosPorProjeto('Ensaio', $projeto->getId(), 10);
        
        $shows = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodosPassadosPorProjeto('Show', $projeto->getId(), 10);
        
        $ensaiosFuturos = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodosFuturosPorProjeto('Ensaio', $projeto->getId(), 10);
        
        $showsFuturos = $em->getRepository('RepSiteBundle:Evento')
                ->listarTodosFuturosPorProjeto('Show', $projeto->getId(), 10);
//                ->findBy(array('tipoEvento' => '3f2a14cb-d10e-11e6-9893-02010202060f', 
//                    'projeto' => $projeto->getId()), null, 10);
        
        $musicasProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($projeto->getSlug(), 0);
        
        $musicasAtivas = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjetoRetornaMusica($projeto->getSlug(), 0);
        
        $musicas = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorDataExecucaoPorProjeto($projeto->getId());
        
        $musicasProjetoFila = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($projeto->getSlug(), 1);
        
        $estilos = $em->getRepository('RepSiteBundle:Estilo')
                ->listarTodosPorProjeto($projeto->getSlug(), 0);
        
        $repertorios = $em->getRepository('RepSiteBundle:Repertorio')
                ->listarTodosPorProjeto($projeto->getId());
        
        return $this->render('RepSiteBundle:Page:musicas-projetos.html.twig', array(
            'usuario' => $user,
            'projeto' => $projeto,
            'musicasProjeto' => $musicasProjeto,
            'musicasProjetoFila' => $musicasProjetoFila,
            'ensaios' => $ensaios,
            'shows' => $shows,
            'ensaiosFuturos' => $ensaiosFuturos,
            'showsFuturos' => $showsFuturos,
            'musicas' => $musicas,
            'musicasAtivas' => $musicasAtivas,
            'estilos' => $estilos,
            'repertorios' => $repertorios,
            'formMusicaProjeto' => $formMusicaProjeto->createView()
        ));
    }
    
    public function estilosAction()
    {
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $estiloType = new EstiloType();
        $formEstilo = $this->createForm($estiloType);
        
        $estilos = $em->getRepository('RepSiteBundle:Estilo')
                ->listarTodos();
        
        return $this->render('RepSiteBundle:Page:estilos.html.twig', array(
            'usuario' => $user,
            'estilos' => $estilos,
            'formEstilo' => $formEstilo->createView()
        ));
    }
    
    public function populaTomAction()
    {
        
        set_time_limit(0);
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $estiloType = new EstiloType();
        $formEstilo = $this->createForm($estiloType);
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')
                ->listarTodas();
        
        //$cont = 1;
        
        foreach($musicas as $musica){
            
            //if($cont <= 10){
                $this->buscaTomMusica($musica, $em);
            //}
            
            //$cont++;
            
        }
        
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\Response();
    }
    
    static function buscaTomMusica(Musica $musica, \Doctrine\ORM\EntityManager $em){
        
        $html = new simple_html_dom();
        $html->load_file("http://www.cifraclub.com.br/".$musica->getArtista()->getSlug()."/".$musica->getSlug()."/");
        
//        $html = file_get_html("http://www.cifraclub.com.br/".$musica->getArtista()->getSlug()."/".$musica->getSlug()."/", $use_include_path = false, 
//                $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, 
//                $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT);
        
        $tom = $html->find('span#cifra_tom a');
        //$cifra = $html->find('div.cifra-mono pre');
        
//        dump($tom);
//        dump($cifra);
        
        if($tom){
            $musica->setTom($tom[0]->text());
            //$musica->setCifra($cifra);
            $em->persist($musica);
        }
        
    }
    
}
