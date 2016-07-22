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
                ->findBy(array('evento' => $id_evento, 'status' => 0));
        
        $musicasAtivas = $em->getRepository('RepSiteBundle:MusicaEvento')->listaMusicasAtivasAusentesNoEvento($id_evento);
        
        //die(var_dump($musicasAtivas));
        
        return $this->render('RepSiteBundle:MusicaEvento:musicas-evento.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicasEvento' => $musicasEvento,
                    'musicasAtivas' => $musicasAtivas
                ));
        
    }
    
    function atualizaMusicasEventoAction($id_evento){
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $this->get('request')->request->get('musicas');
        
        $em->getRepository('RepSiteBundle:MusicaEvento')->invalidaTodasMusicasEvento($id_evento);
        
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
            $musicaEvento->setObservacao($dados[1]);
            
            $em->persist($musicaEvento);
            
        }
        
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\Response();
        
    }
    
}
