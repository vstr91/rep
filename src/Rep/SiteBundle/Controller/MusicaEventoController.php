<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\MusicaEvento;
use Rep\SiteBundle\Form\MusicaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class MusicaEventoController extends Controller {
    
    public function musicasEventoAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($id_evento);
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')->findBy(array('status' => 0));
        
        $referer = $request->headers->get('referer');
        
        return $this->render('RepSiteBundle:MusicaEvento:musicas-evento.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicasEvento' => $musicasEvento,
                    'musicasAtivas' => $musicas,
                    'referer' => $referer
                ));
        
    }
    
    function atualizaMusicasEventoAction($id_evento){
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $this->get('request')->request->get('musicas');
        
        $em->getRepository('RepSiteBundle:MusicaEvento')->invalidaTodasMusicasEvento($id_evento);
        
        $cont = 1;
        
        foreach($musicas as $musica){
            
            $dados = explode("|", $musica);
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $dados[0]);
            $evento = $em->find('RepSiteBundle:Evento', $id_evento);
            
            $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                    findOneBy(array('musica' => $umaMusica, 'evento' => $evento));
            
            if($musicaEvento == null){
                $musicaEvento = new MusicaEvento();
            }
            
            $musicaEvento->setMusica($umaMusica);
            $musicaEvento->setEvento($evento);
            
            $musicaEvento->setStatus(0);
            $musicaEvento->setOrdem($cont);
            $musicaEvento->setObservacao($dados[1]);
            
            $em->persist($musicaEvento);
            
            $cont++;
            
        }
        
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\Response();
        
    }
    
    public function formCadastraMusicaAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listaMusicasAtivasAusentesNoEvento($id_evento);
        
        if(sizeof($musicasEvento) == 0){
            $musicasEvento = $em->getRepository('RepSiteBundle:Musica')->listarTodasAtivas();
        }
        
        return $this->render('RepSiteBundle:MusicaEvento:adiciona-musica.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicas' => $musicasEvento
                ));
        
    }
    
    public function cadastraMusicaAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        foreach($musicas as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica);
            
            $umaMusicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                findOneBy(array('musica' => $umaMusica, 'evento' => $evento));
            
            $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($id_evento);
            
        $total = count($musicasEvento)+1;
        //die("Total: ".$total);
            
            if($umaMusicaEvento == null){
                $umaMusicaEvento = new MusicaEvento();
            }
            
            $umaMusicaEvento->setMusica($umaMusica);
            $umaMusicaEvento->setEvento($evento);
            $umaMusicaEvento->setStatus(0);
            $umaMusicaEvento->setOrdem($total);
            
            $em->persist($umaMusicaEvento);
            
            if($umaMusica->getStatus() != 0){
                $umaMusica->setStatus(0);
                $em->persist($umaMusica);
            }
            
        }
        
        $em->flush();
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($id_evento);
        
        return $this->redirect($this->generateUrl('rep_site_musicas_evento', array('id_evento' => $id_evento, 
            'musicas' => $musicasEvento)));
        
    }
    
    public function carregaRepertorioAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($id_evento);
        
        return $this->render('RepSiteBundle:MusicaEvento:tabela-repertorio.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicas' => $musicasEvento
                ));
        
    }
    
    public function removeMusicaEventoAction($id_evento, $id_musica){
        $evento = null;
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musica = $em->find('RepSiteBundle:Musica', $id_musica);
        
        $musicaEvento = new MusicaEvento();
        
        $musicaEvento->setMusica($musica);
        $musicaEvento->setEvento($evento);
        
        $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                findOneBy(array('musica' => $musica, 'evento' => $evento));
        
        $musicaEvento->setStatus(2);
        
        $em->persist($musicaEvento);
        
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\Response();
        
    }
    
    function ordemMusicaEventoAction($id_evento){
        
        $em = $this->getDoctrine()->getManager();
        
        $ordem = $this->get('request')->request->get('ordem');
        
        //die(var_dump($ordem));
        
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        foreach($ordem as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica['id']);
            
            $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                    findOneBy(array('musica' => $umaMusica, 'evento' => $evento));
            
            if($musicaEvento == null){
                $musicaEvento = new MusicaEvento();
            }
            
            $musicaEvento->setMusica($umaMusica);
            $musicaEvento->setEvento($evento);
            
            $musicaEvento->setStatus(0);
            $musicaEvento->setOrdem($musica['ordem']);
            
            $em->persist($musicaEvento);
            
        }
        
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\Response('ok');
        
    }
    
    public function cadastraIntervaloAction($id_evento){
        
        $evento = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);

        $umaMusicaEvento = new MusicaEvento();
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
            ->listarTodasPorEvento($id_evento);

        $total = count($musicasEvento)+1;

        $umaMusicaEvento->setMusica(null);
        $umaMusicaEvento->setEvento($evento);
        $umaMusicaEvento->setStatus(0);
        $umaMusicaEvento->setOrdem($total);
        
        $em->persist($umaMusicaEvento);
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\Response('ok');
        
    }
    
}
