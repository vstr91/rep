<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Artista;
use Rep\SiteBundle\Form\ArtistaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class ArtistaController extends Controller {
    
    public function cadastrarAction($id_artista){
        $artista = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $artista = $em->find('RepSiteBundle:Artista', $id_artista);
        
        //se nao existir, cria novo objeto
        if(is_null($artista)){
            $artista = new Artista();
        }
        
        $form = $this->createForm(new ArtistaType(), $artista);
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
            
            $em->persist($artista);
            $em->flush();
            
            return $this->redirect($this->generateUrl('rep_site_artistas'));
        }
        
        $artistas = $em->getRepository('RepSiteBundle:Artista')
                ->findAll();
        
        return $this->render('RepSiteBundle:Page:artistas.html.twig', 
                array(
                    'usuario' => $user,
                    'artistas' => $artistas,
                    'formArtista' => $form->createView()
                ));
        
    }
    
    public function formAction($id_artista){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $artista = $em->find('RepSiteBundle:Artista', $id_artista);
        
        if(is_null($artista)){
            $artista = new Artista();
        }
        
        $form = $this->createForm(new ArtistaType(), $artista);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Artista:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'artista' => $artista
                ));
    }
    
    public function musicasAction($id_artista){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $musicas = $em->getRepository('RepSiteBundle:Musica')
                ->findBy(array('artista' => $id_artista), array('nome' => 'ASC'));
        
        return $this->render('RepSiteBundle:Artista:musicas.html.twig',
                array(
                    'musicas' => $musicas
                ));
    }
    
    public function formNovoAction($id_artista){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $artista = $em->find('RepSiteBundle:Artista', $id_artista);
        
        if(is_null($artista)){
            $artista = new Artista();
        }
        
        $form = $this->createForm(new ArtistaType(), $artista);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Artista:form-novo.html.twig',
                array(
                    'form' => $form->createView(),
                    'artista' => $artista
                ));
    }
    
    public function cadastrarNovoAction($id_artista){
        $artista = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $artista = $em->find('RepSiteBundle:Artista', $id_artista);
        
        //se nao existir, cria novo objeto
        if(is_null($artista)){
            $artista = new Artista();
        }
        
        $form = $this->createForm(new ArtistaType(), $artista);
        $form->bind($request);
        
        if($form->isValid()){
            
            //cadastra ou edita objeto
            
            $em->persist($artista);
            $em->flush();
            
            return new \Symfony\Component\HttpFoundation\Response('ok');
        }
        
        return new \Symfony\Component\HttpFoundation\Response('nok');
        
    }
    
}
