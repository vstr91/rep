<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\MusicaEvento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ComentarioEventoController
 *
 * @author Almir
 */
class ComentarioEventoController extends Controller {
    
    public function cadastraComentarioAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $comentario = $request->request->get('comentario');
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $comentarioEvento = new \Rep\SiteBundle\Entity\ComentarioEvento();
        $comentarioEvento->setTexto($comentario);
        $comentarioEvento->setEvento($evento);
        
        $em->persist($comentarioEvento);
        
        $em->flush();
        
        return new Response('ok');
        
    }
    
    public function carregaComentariosAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $comentarios = $em->getRepository('RepSiteBundle:ComentarioEvento')
                ->findBy(array("evento" => $id_evento));
        
        return $this->render('RepSiteBundle:ComentarioEvento:lista-cadastrados.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'comentarios' => $comentarios
                ));
        
    }
    
}
