<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Rep\SiteBundle\Entity\APIToken;
use Rep\SiteBundle\Entity\MCrypt;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of RepRestController
 *
 * @author Almir
 */
class RepRestController extends FOSRestController {
    
    public function getTokenAction(){
        $crypt = new MCrypt();
        $apiToken = new APIToken();
        $em = $this->getDoctrine()->getManager();
        
        $request = $this->getRequest();
        
        $identificadorUnico = $request->get("id");
        
        if($identificadorUnico != null){
            $identificadorUnico = $crypt->decrypt($identificadorUnico);
        } else{
            $identificadorUnico = "";
        }
        
        $puroTexto = $crypt->geraChaveAcessoAPI(10);
        
        $apiToken->setPuroTexto($puroTexto);
        $apiToken->setIdentificadorUnico($identificadorUnico);
        
        $token = $crypt->encrypt($puroTexto);
        
        $em->persist($apiToken);
        $em->flush();
        
        return new Response($token);
    }
    
    public function getDadosAction($hash, $data) {
        $em = $this->getDoctrine()->getManager();
        
        $crypto = new MCrypt();
        
        $hashDescriptografado = $crypto->decrypt($crypto->decrypt($hash));
        
        if(null != $em->getRepository('RepSiteBundle:APIToken')->validaToken($hashDescriptografado)){
            $data = $data == '-' ? '2000-01-01' : $data;

            $artistas = $em->getRepository('RepSiteBundle:Artista')
                    ->listarTodosREST(null, $data);
            $tiposEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                    ->listarTodosREST(null, $data);
            $musicas = $em->getRepository('RepSiteBundle:Musica')
                    ->listarTodosREST(null, $data);
            $eventos = $em->getRepository('RepSiteBundle:Evento')
                    ->listarTodosREST(null, $data);
            $musicasEventos = $em->getRepository('RepSiteBundle:MusicaEvento')
                    ->listarTodosREST(null, $data);
            $comentariosEventos = $em->getRepository('RepSiteBundle:ComentarioEvento')
                    ->listarTodosREST(null, $data);
            
//            $log = $em->getRepository('RepSiteBundle:LogEntry')
//                    ->listarTodosREST(null, $data);

            $totalRegistros = count($artistas) + count($tiposEvento) + count($musicas) 
                    + count($eventos) + count($musicasEventos) + count($comentariosEventos);
            
            $view = View::create(
                    array(
                        "meta" => array(array("registros" => $totalRegistros, "status" => 200, "mensagem" => "ok")),
                        "artistas" => $artistas, 
                        "tipos_eventos" => $tiposEvento,
                        "musicas" => $musicas,
                        "eventos" => $eventos,
                        "musicas_eventos" => $musicasEventos,
                        "comentarios_eventos" => $comentariosEventos//,
                        //"log" => $log
                    ), 200, array('totalRegistros' => $totalRegistros))->setTemplateVar("u");
            
            $em->getRepository('RepSiteBundle:APIToken')->atualizaToken($hashDescriptografado);


            return $this->handleView($view);            
        } else {
            $view = View::create(
                    array(
                        "meta" => array(array("registros" => 0, "status" => 403, "mensagem" => "Acesso negado."))
                    ),
                403, array('totalRegistros' => 0))->setTemplateVar("u");
            
            return $this->handleView($view);
        }
        
    }
    
    public function setDadosAction($hash, $data) {
        $em = $this->getDoctrine()->getManager();
        
        $crypto = new MCrypt();
        
        $hashDescriptografado = $crypto->decrypt($crypto->decrypt($hash));
        
        if(null != $em->getRepository('RepSiteBundle:APIToken')->validaToken($hashDescriptografado)){
            
            $dados = $this->getRequest()->request->all();
            $total = $dados['total'];
            $dados = json_decode($dados['dados'], TRUE);
            
            //die(var_dump($dados));
            
            $comentarios = $dados['comentarios'];
            
            $processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $umComentario = new \Rep\SiteBundle\Entity\ComentarioEvento();
                $umComentario->setId($comentarios[$i]['id']);
                $umComentario->setTexto($comentarios[$i]['texto']);
                
                $umEvento = new \Rep\SiteBundle\Entity\Evento();
                $umEvento = $em->getRepository('RepSiteBundle:Evento')
                        ->findOneBy(array('id' => $comentarios[$i]['evento']));
                
                $umComentario->setEvento($umEvento);
                $umComentario->setStatus(0);
//                $umaMensagem->setDataCriacao($mensagem[0]['descricao'])

                $em->persist($umComentario);
                
                $metadata = $this->em->getClassMetaData(get_class($umComentario));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
                
                $processadas[] = $comentarios[$i]['id'];
            }
            
            //die(var_dump($processadas));
            
            $em->flush();
            
            $view = View::create(
                            array(
                        "meta" => array(array("registros" => count($processadas), "status" => 200, "mensagem" => "ok")),
                        "processadas" => $processadas
                            ), 200, array('totalRegistros' => count($processadas)))->setTemplateVar("u");
            
//            $em->getRepository('CircularSiteBundle:APIToken')->atualizaToken($hashDescriptografado);

            return $this->handleView($view);           
        } else {
            $view = View::create(
                    array(
                        "meta" => array(array("registros" => 0, "status" => 403, "mensagem" => "Acesso negado."))
                    ),
                403, array('totalRegistros' => 0))->setTemplateVar("u");
            
            return $this->handleView($view);
        }
        
    }
    
}
