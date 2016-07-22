<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\TipoEvento;
use Rep\SiteBundle\Form\TipoEventoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class TipoEventoController extends Controller {
    
    public function cadastrarAction($id_tipo_evento){
        $tipoEvento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $tipoEvento = $em->find('RepSiteBundle:TipoEvento', $id_tipo_evento);
        
        //se nao existir, cria novo objeto
        if(is_null($tipoEvento)){
            $tipoEvento = new TipoEvento();
        }
        
        $form = $this->createForm(new TipoEventoType(), $tipoEvento);
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
            
            $em->persist($tipoEvento);
            $em->flush();
            
            return $this->redirect($this->generateUrl('rep_site_tipos_evento'));
        }
        
        $tiposEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:tipos-evento.html.twig', 
                array(
                    'usuario' => $user,
                    'tiposEvento' => $tiposEvento,
                    'formTipoEvento' => $form->createView()
                ));
        
    }
    
    public function formAction($id_tipo_evento){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $tipoEvento = $em->find('RepSiteBundle:TipoEvento', $id_tipo_evento);
        
        if(is_null($tipoEvento)){
            $tipoEvento = new TipoEvento();
        }
        
        $form = $this->createForm(new TipoEventoType(), $tipoEvento);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:TipoEvento:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'tipoEvento' => $tipoEvento
                ));
    }
    
}
