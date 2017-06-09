<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Estilo;
use Rep\SiteBundle\Form\EstiloType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of EstiloController
 *
 * @author Almir
 */
class EstiloController extends Controller {
    
    public function cadastrarAction($id_estilo){
        $estilo = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $estilo = $em->find('RepSiteBundle:Estilo', $id_estilo);
        
        //se nao existir, cria novo objeto
        if(is_null($estilo)){
            $estilo = new Estilo();
        }
        
        $form = $this->createForm(new EstiloType(), $estilo);
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
            
            $em->persist($estilo);
            $em->flush();
            
            return $this->redirect($this->generateUrl('rep_site_estilos'));
        }
        
        $estilos = $em->getRepository('RepSiteBundle:Estilo')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:estilos.html.twig', 
                array(
                    'usuario' => $user,
                    'estilos' => $estilos,
                    'formEstilo' => $form->createView()
                ));
        
    }
    
    public function formAction($id_estilo){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $estilo = $em->find('RepSiteBundle:Estilo', $id_estilo);
        
        if(is_null($estilo)){
            $estilo = new Estilo();
        }
        
        $form = $this->createForm(new EstiloType(), $estilo);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Estilo:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'estilo' => $estilo
                ));
    }
    
    public function musicasAction($id_estilo){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $musicas = $em->getRepository('RepSiteBundle:Musica')
                ->findBy(array('estilo' => $id_estilo), array('nome' => 'ASC'));
        
        return $this->render('RepSiteBundle:Estilo:musicas.html.twig',
                array(
                    'musicas' => $musicas
                ));
    }
    
    public function formNovoAction($id_estilo){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $estilo = $em->find('RepSiteBundle:Estilo', $id_estilo);
        
        if(is_null($estilo)){
            $estilo = new Estilo();
        }
        
        $form = $this->createForm(new EstiloType(), $estilo);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Estilo:form-novo.html.twig',
                array(
                    'form' => $form->createView(),
                    'estilo' => $estilo
                ));
    }
    
    public function cadastrarNovoAction($id_estilo){
        $estilo = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $estilo = $em->find('RepSiteBundle:Estilo', $id_estilo);
        
        //se nao existir, cria novo objeto
        if(is_null($estilo)){
            $estilo = new Estilo();
        }
        
        $form = $this->createForm(new EstiloType(), $estilo);
        $form->bind($request);
        
        if($form->isValid()){
            
            //cadastra ou edita objeto
            
            $em->persist($estilo);
            $em->flush();
            
            return new \Symfony\Component\HttpFoundation\Response('ok');
        }
        
        return new \Symfony\Component\HttpFoundation\Response('nok');
        
    }
    
}
