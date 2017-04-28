<?php

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Projeto;
use Rep\SiteBundle\Form\ProjetoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProjetoController
 *
 * @author Almir
 */
class ProjetoController extends Controller {
    
    public function cadastrarAction($id_projeto){
        $projeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        //se nao existir, cria novo objeto
        if(is_null($projeto)){
            $projeto = new Projeto();
        }
        
        $form = $this->createForm(new ProjetoType(), $projeto);
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
            
            $em->persist($projeto);
            $em->flush();
            
            return $this->redirect($this->generateUrl('rep_site_projetos'));
        }
        
        $projetos = $em->getRepository('RepSiteBundle:Projeto')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:projetos.html.twig', 
                array(
                    'usuario' => $user,
                    'projetos' => $projetos,
                    'formProjeto' => $form->createView()
                ));
        
    }
    
    public function mudaAction(){
        $em = $this->getDoctrine()->getManager();
        
        $projetos = $em->getRepository('RepSiteBundle:Projeto')
                ->findBy(array('status' => 0));
        
        return $this->render('RepSiteBundle:Projeto:muda-projeto.html.twig', 
                array(
                    'projetos' => $projetos
                ));
        
    }
    
    public function formAction($id_projeto){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        if(is_null($projeto)){
            $projeto = new Projeto();
        }
        
        $form = $this->createForm(new ProjetoType(), $projeto);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Projeto:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'projeto' => $projeto
                ));
    }
    
}
