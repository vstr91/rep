<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Musica;
use Rep\SiteBundle\Form\MusicaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of MusicaController
 *
 * @author Almir
 */
class MusicaController extends Controller {
    
    public function cadastrarAction($id_musica){
        $musica = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $musica = $em->find('RepSiteBundle:Musica', $id_musica);
        
        //se nao existir, cria novo objeto
        if(is_null($musica)){
            $musica = new Musica();
        }
        
        $form = $this->createForm(new MusicaType(), $musica);
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
            
            $em->persist($musica);
            $em->flush();
            
            $referer = $request->headers->get('referer');
            
//            return $this->redirect($this->generateUrl('rep_site_musicas'));
            return $this->redirect($referer);
        }
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')
                ->listarTodas();//findAll();
        
        return $this->render('RepSiteBundle:Page:musicas.html.twig', 
                array(
                    'usuario' => $user,
                    'musicas' => $musicas,
                    'formMusica' => $form->createView()
                ));
        
    }
    
    public function formAction($id_musica){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $musica = $em->find('RepSiteBundle:Musica', $id_musica);
        
        if(is_null($musica)){
            $musica = new Musica();
        }
        
        $form = $this->createForm(new MusicaType(), $musica);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:Musica:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'musica' => $musica
                ));
    }
    
    public function detalhesAction($artista, $slug, $id){
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($id > -1){
            $musica = $em->getRepository('RepSiteBundle:Musica')->find($id);
        } else{
            $artista = $em->getRepository('RepSiteBundle:Artista')->findOneBy(array('slug' => $artista));
            $musica = $em->getRepository('RepSiteBundle:Musica')->findOneBy(array('slug' => $slug, 'artista' => $artista->getId()));
        }
        
        $eventos = $em->getRepository('RepSiteBundle:MusicaEvento')->listarEventosMusica($musica->getId(), null);
        $shows = $em->getRepository('RepSiteBundle:MusicaEvento')->listarEventosMusica($musica->getId(), 'Show');
        $ultimoShow = $em->getRepository('RepSiteBundle:MusicaEvento')->listarUltimoEventoMusica($musica->getId(), 'Show');
        $ultimoEnsaio = $em->getRepository('RepSiteBundle:MusicaEvento')->listarUltimoEventoMusica($musica->getId(), 'Ensaio');
        
        $eventosCarregados = array();
        $showsCarregados = array();
        
        foreach($eventos as $ev){
            $umMusicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->find($ev->getId());
            $eventosCarregados[] = $umMusicaEvento;
        }
        
        foreach($shows as $sh){
            $umMusicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->find($sh->getId());
            $showsCarregados[] = $umMusicaEvento;
        }
        
        foreach($ultimoShow as $us){
            $umEvento = $em->getRepository('RepSiteBundle:Evento')->find($us->getId());
            $ultimoShow = $umEvento;
        }
        
        foreach($ultimoEnsaio as $ue){
            $umEvento = $em->getRepository('RepSiteBundle:Evento')->find($ue->getId());
            $ultimoEnsaio = $umEvento;
        }
        
        return $this->render('RepSiteBundle:Musica:detalhes.html.twig', 
                array(
                    'usuario' => $user,
                    'musica' => $musica,
                    'eventos' => $eventosCarregados,
                    'ultimoShow' => $ultimoShow,
                    'ultimoEnsaio' => $ultimoEnsaio,
                    'shows' => $showsCarregados
                ));
        
    }
    
}
