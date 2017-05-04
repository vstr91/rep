<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Evento;
use Rep\SiteBundle\Form\EventoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class EventoController extends Controller {
    
    public function cadastrarAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        //se nao existir, cria novo objeto
        if(is_null($evento)){
            $evento = new Evento();
        }
        
        $form = $this->createForm(new EventoType(), $evento);
        $form->bind($request);
        
        if($form->isValid()){
            
            //verifica se o objeto sera inativado. Em caso positivo, verifica se existem registros ativos vinculados
            //a ele. Se existirem, nao deixa inativar
            
//            if($empresa->getStatus() == 2){
//                
//                $registrosVinculados = $em->getRepository('CircularSiteBundle:Empresa')
//                        ->listarRegistrosVinculados($empresa)['total'];
//                
//                if($registrosVinculados > 0){
//                    
//                    $flashBag = $this->get('session')->getFlashBag();
//                    $flashBag->get('aviso-empresa'); // gets message and clears type
//                    $flashBag->set('aviso-empresa', 'Empresa contém registros ativos vinculados '
//                            . 'e não pode ser inativada. Nenhuma alteração foi realizada.');
//                    return $this->redirect($this->generateUrl('circular_site_admin_empresas'));
//                }
//                
//            }
            
            //cadastra ou edita objeto
            
            $em->persist($evento);
            $em->flush();
            
            $referer = $request->headers->get('referer');
            
            return $this->redirect($referer);
        }
        
        $tiposEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                ->findAll();
        
        $eventos = $em->getRepository('RepSiteBundle:Evento')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:eventos.html.twig', 
                array(
                    'usuario' => $user,
                    'eventos' => $eventos,
                    'tiposEvento' => $tiposEvento,
                    'formEvento' => $form->createView()
                ));
        
    }
    
    public function formAction($id_evento){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        if(is_null($evento)){
            $evento = new Evento();
        }
        
        $form = $this->createForm(new EventoType(), $evento);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Evento:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'evento' => $evento
                ));
    }
    
    public function eventosDataAction($data){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        $eventos = $em->getRepository('RepSiteBundle:Evento')->listarTodosPorDia($data);
                //->findBy(array('data' => '2017-02-28 00:00:00'), array('data' => 'DESC'));
        
        return $this->render('RepSiteBundle:Evento:eventos-dia.html.twig',
                array(
                    'eventos' => $eventos,
                    'data' => $data
                ));
        
    }
    
}
