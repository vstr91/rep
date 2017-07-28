<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Repertorio;
use Rep\SiteBundle\Form\RepertorioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class RepertorioController extends Controller {
    
    public function cadastrarAction($id_projeto, $id_repertorio){
        $repertorio = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        
        //se nao existir, cria novo objeto
        if(is_null($repertorio)){
            $repertorio = new Repertorio();
        }
        
        $form = $this->createForm(new RepertorioType(), $repertorio);
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
            
            $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
            $repertorio->setProjeto($projeto);
            
            $em->persist($repertorio);
            $em->flush();
            
            $referer = $request->headers->get('referer');
            
            return $this->redirect($referer);
        }
        
        $repertorios = $em->getRepository('RepSiteBundle:Repertorio')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:repertorios.html.twig', 
                array(
                    'usuario' => $user,
                    'repertorios' => $repertorios,
                    'formRepertorio' => $form->createView()
                ));
        
    }
    
    public function formAction($id_projeto, $id_repertorio){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        if(is_null($repertorio)){
            $repertorio = new Repertorio();
        }
        
        $repertorio->setProjeto($projeto);
        
        $form = $this->createForm(new RepertorioType(), $repertorio);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Repertorio:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'repertorio' => $repertorio,
                    'projeto' => $projeto
                ));
    }
    
}
