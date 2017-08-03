<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Rep\SiteBundle\Entity\APIToken;
use Rep\SiteBundle\Entity\Artista;
use Rep\SiteBundle\Entity\ComentarioEvento;
use Rep\SiteBundle\Entity\Estilo;
use Rep\SiteBundle\Entity\Evento;
use Rep\SiteBundle\Entity\MCrypt;
use Rep\SiteBundle\Entity\Musica;
use Rep\SiteBundle\Entity\MusicaEvento;
use Rep\SiteBundle\Entity\MusicaProjeto;
use Rep\SiteBundle\Entity\MusicaRepertorio;
use Rep\SiteBundle\Entity\Projeto;
use Rep\SiteBundle\Entity\Repertorio;
use Rep\SiteBundle\Entity\TempoMusicaEvento;
use Rep\SiteBundle\Entity\TipoEvento;
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
            
            $estilos = $em->getRepository('RepSiteBundle:Estilo')
                    ->listarTodosREST(null, $data);
            $estilosMusicas = $em->getRepository('RepSiteBundle:EstiloMusica')
                    ->listarTodosREST(null, $data);
            $projetos = $em->getRepository('RepSiteBundle:Projeto')
                    ->listarTodosREST(null, $data);
            $musicasProjetos = $em->getRepository('RepSiteBundle:MusicaProjeto')
                    ->listarTodosREST(null, $data);
            $temposMusicasEventos = $em->getRepository('RepSiteBundle:TempoMusicaEvento')
                    ->listarTodosREST(null, $data);
            
            $repertorios = $em->getRepository('RepSiteBundle:Repertorio')
                    ->listarTodosREST(null, $data);
            
            $musicasRepertorios = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                    ->listarTodosREST(null, $data);
            
//            $log = $em->getRepository('RepSiteBundle:LogEntry')
//                    ->listarTodosREST(null, $data);

            $totalRegistros = count($artistas) + count($tiposEvento) + count($musicas) 
                    + count($eventos) + count($musicasEventos) + count($comentariosEventos)
                    + count($estilos) + count($estilosMusicas) + count($projetos) + count($musicasProjetos) + count($temposMusicasEventos) 
                    + count($repertorios) + count($musicasRepertorios);
            
            $view = View::create(
                    array(
                        "meta" => array(array("registros" => $totalRegistros, "status" => 200, "mensagem" => "ok")),
                        "artistas" => $artistas, 
                        "tipos_eventos" => $tiposEvento,
                        "musicas" => $musicas,
                        "eventos" => $eventos,
                        "musicas_eventos" => $musicasEventos,
                        "comentarios_eventos" => $comentariosEventos,
                        "estilos" => $estilos,
                        "estilosMusicas" => $estilosMusicas,
                        "projetos" => $projetos,
                        "musicas_projetos" => $musicasProjetos,
                        "tempos_musicas_eventos" => $temposMusicasEventos,
                        "repertorios" => $repertorios,
                        "musicas_repertorios" => $musicasRepertorios
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
            $dados = json_decode($dados['dados'], TRUE);
            
            //die(var_dump($dados['musicas']));
            
            $comentarios = $dados['comentarios'];
            $total = count($comentarios);
            
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $umComentario = new ComentarioEvento();
                $umComentario->setId($comentarios[$i]['id']);
                $umComentario->setTexto($comentarios[$i]['texto']);
                
                $umEvento = new Evento();
                $umEvento = $em->getRepository('RepSiteBundle:Evento')
                        ->findOneBy(array('id' => $comentarios[$i]['evento']));
                
                $umComentario->setEvento($umEvento);
                $umComentario->setStatus(0);
//                $umaMensagem->setDataCriacao($mensagem[0]['descricao'])

                $em->persist($umComentario);
                
                $metadata = $em->getClassMetaData(get_class($umComentario));
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                $metadata->setIdGenerator(new AssignedGenerator());
                $umComentario->setId($comentarios[$i]['id']);
                
                //$processadas[] = $comentarios[$i]['id'];
            }
            
            // ESTILOS
            
            $estilos = $dados['estilos'];
            $total = count($estilos);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umEstilo = null;
                
                $umEstilo = $em->getRepository('RepSiteBundle:Estilo')
                        ->findOneBy(array('id' => $estilos[$i]['id']));
                
                if($umEstilo == null){
                    $umEstilo = new Estilo();
                    $umEstilo->setId($estilos[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umEstilo->setNome($estilos[$i]['nome']);
                $umEstilo->setStatus($estilos[$i]['status']);
                $umEstilo->setSlug(NULL);
                //$umArtista->setDataCadastro($artistas[$i]['data_cadastro']);

                //die(var_dump($umArtista));
                
                $em->persist($umEstilo);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umEstilo));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umEstilo->setId($estilos[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // PROJETOS
            
            $projetos = $dados['projetos'];
            $total = count($projetos);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umProjeto = null;
                
                $umProjeto = $em->getRepository('RepSiteBundle:Projeto')
                        ->findOneBy(array('id' => $projetos[$i]['id']));
                
                if($umProjeto == null){
                    $umProjeto = new Projeto();
                    $umProjeto->setId($projetos[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umProjeto->setNome($projetos[$i]['nome']);
                $umProjeto->setStatus($projetos[$i]['status']);
                $umProjeto->setSlug(NULL);
                //$umArtista->setDataCadastro($artistas[$i]['data_cadastro']);

                //die(var_dump($umArtista));
                
                $em->persist($umProjeto);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umProjeto));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umProjeto->setId($projetos[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // ARTISTAS
            
            $artistas = $dados['artistas'];
            $total = count($artistas);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umArtista = null;
                
                $umArtista = $em->getRepository('RepSiteBundle:Artista')
                        ->findOneBy(array('id' => $artistas[$i]['id']));
                
                if($umArtista == null){
                    $umArtista = new Artista();
                    $umArtista->setId($artistas[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umArtista->setNome($artistas[$i]['nome']);
                $umArtista->setStatus($artistas[$i]['status']);
                $umArtista->setSlug(NULL);
                //$umArtista->setDataCadastro($artistas[$i]['data_cadastro']);

                //die(var_dump($umArtista));
                
                $em->persist($umArtista);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umArtista));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umArtista->setId($artistas[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // MUSICAS
            
            $musicas = $dados['musicas'];
            $total = count($musicas);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                //$umMusica = null;
                $umMusica = null;
                
                $umMusica = $em->getRepository('RepSiteBundle:Musica')
                        ->findOneBy(array('id' => $musicas[$i]['id']));
                
                if($umMusica == null){
                    $umMusica = new Musica();
                    $umMusica->setId($musicas[$i]['id']);
                    //$umMusica->setDataCadastro($musicas[$i]['data_cadastro']);
                } else{
                    $existe = true;
                }
                
                $umMusica->setId($musicas[$i]['id']);
                $umMusica->setNome($musicas[$i]['nome']);
                $umMusica->setTom($musicas[$i]['tom']);
                
                $umEstilo = new Estilo();
                $umEstilo = $em->getRepository('RepSiteBundle:Estilo')
                        ->findOneBy(array('id' => $musicas[$i]['estilo']));
                
                $umMusica->setEstilo($umEstilo);
                
                $umArtista = new Artista();
                $umArtista = $em->getRepository('RepSiteBundle:Artista')
                        ->findOneBy(array('id' => $musicas[$i]['artista']));
                
                $umMusica->setArtista($umArtista);
                $umMusica->setStatus($musicas[$i]['status']);
                $umMusica->setSlug(NULL);

                $umMusica->setLetra($musicas[$i]['letra']);
                $umMusica->setObservacoes($musicas[$i]['observacoes']);
                
                $em->persist($umMusica);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umMusica));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umMusica->setId($musicas[$i]['id']);
                    $em->flush();
                    
                }
            }
            
            // EVENTOS
            
            $eventos = $dados['eventos'];
            $total = count($eventos);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umEvento = null;
                
                $umEvento = $em->getRepository('RepSiteBundle:Evento')
                        ->findOneBy(array('id' => $eventos[$i]['id']));
                
                if($umEvento == null){
                    $umEvento = new Evento();
                    $umEvento->setId($eventos[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umEvento->setNome($eventos[$i]['nome']);
                $umEvento->setStatus($eventos[$i]['status']);
                $umEvento->setData(date_create_from_format('Y-m-d\TH:i:sO', $eventos[$i]['data']));
                $umEvento->setSlug(NULL);
                
                $umTipoEvento = new TipoEvento();
                $umTipoEvento = $em->getRepository('RepSiteBundle:TipoEvento')
                        ->findOneBy(array('id' => $eventos[$i]['tipo_evento']));
                
                $umEvento->setTipoEvento($umTipoEvento);
                
                $umProjeto = new Projeto();
                $umProjeto = $em->getRepository('RepSiteBundle:Projeto')
                        ->findOneBy(array('id' => $eventos[$i]['projeto']));
                
                $umEvento->setProjeto($umProjeto);
                
                $em->persist($umEvento);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umEvento));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umEvento->setId($eventos[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // MUSICAS EVENTOS
            
            $musicasEventos = $dados['musicas_eventos'];
            $total = count($musicasEventos);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umMusicaEvento = null;
                
                $umMusicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                        ->findOneBy(array('id' => $musicasEventos[$i]['id']));
                
                if($umMusicaEvento == null){
                    $umMusicaEvento = new MusicaEvento();
                    $umMusicaEvento->setId($musicasEventos[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umMusicaEvento->setOrdem($musicasEventos[$i]['ordem']);
                $umMusicaEvento->setStatus($musicasEventos[$i]['status']);
                
                $umMusica = new Musica();
                $umMusica = $em->getRepository('RepSiteBundle:Musica')
                        ->findOneBy(array('id' => $musicasEventos[$i]['musica']));
                
                $umMusicaEvento->setMusica($umMusica);
                
                $umEvento = new Evento();
                $umEvento = $em->getRepository('RepSiteBundle:Evento')
                        ->findOneBy(array('id' => $musicasEventos[$i]['evento']));
                
                $umMusicaEvento->setEvento($umEvento);
                
                $em->persist($umMusicaEvento);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umMusicaEvento));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umMusicaEvento->setId($musicasEventos[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // MUSICAS PROJETOS
            
            $musicasProjetos = $dados['musicas_projetos'];
            $total = count($musicasProjetos);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umMusicaProjeto = null;
                
                $umMusicaProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                        ->findOneBy(array('id' => $musicasProjetos[$i]['id']));
                
                if($umMusicaProjeto == null){
                    $umMusicaProjeto = new MusicaProjeto();
                    $umMusicaProjeto->setId($musicasProjetos[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umMusicaProjeto->setStatus($musicasProjetos[$i]['status']);
                
                $umMusica = new Musica();
                $umMusica = $em->getRepository('RepSiteBundle:Musica')
                        ->findOneBy(array('id' => $musicasProjetos[$i]['musica']));
                
                $umMusicaProjeto->setMusica($umMusica);
                
                $umProjeto = new Projeto();
                $umProjeto = $em->getRepository('RepSiteBundle:Projeto')
                        ->findOneBy(array('id' => $musicasProjetos[$i]['projeto']));
                
                $umMusicaProjeto->setProjeto($umProjeto);
                
                $em->persist($umMusicaProjeto);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umMusicaProjeto));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umMusicaProjeto->setId($musicasProjetos[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // TEMPOS MUSICAS EVENTOS
            ///*
            $temposMusicasEventos = $dados['tempos_musicas_eventos'];
            $total = count($temposMusicasEventos);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umTempoMusicaEvento = null;
                
                $umTempoMusicaEvento = $em->getRepository('RepSiteBundle:TempoMusicaEvento')
                        ->findOneBy(array('id' => $temposMusicasEventos[$i]['id']));
                
                if($umTempoMusicaEvento == null){
                    $umTempoMusicaEvento = new TempoMusicaEvento();
                    $umTempoMusicaEvento->setId($temposMusicasEventos[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umTempoMusicaEvento->setStatus($temposMusicasEventos[$i]['status']);
                $umTempoMusicaEvento->setTempo(date_create_from_format('Y-m-d\TH:i:sO', $temposMusicasEventos[$i]['tempo']));
                
                $umMusicaEvento = new MusicaEvento();
                $umMusicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                        ->findOneBy(array('id' => $temposMusicasEventos[$i]['musica_evento']));
                
                $umTempoMusicaEvento->setMusicaEvento($umMusicaEvento);
                
                $em->persist($umTempoMusicaEvento);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umTempoMusicaEvento));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umTempoMusicaEvento->setId($temposMusicasEventos[$i]['id']);
                }
                
            }
            //*/
            
            // REPERTORIOS
            
            $repertorios = $dados['repertorios'];
            $total = count($repertorios);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umRepertorio = null;
                
                $umRepertorio = $em->getRepository('RepSiteBundle:Repertorio')
                        ->findOneBy(array('id' => $repertorios[$i]['id']));
                
                if($umRepertorio == null){
                    $umRepertorio = new Repertorio();
                    $umRepertorio->setId($repertorios[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umRepertorio->setNome($repertorios[$i]['nome']);
                $umRepertorio->setStatus($repertorios[$i]['status']);
                $umRepertorio->setSlug(NULL);
                
                $umProjeto = new Projeto();
                $umProjeto = $em->getRepository('RepSiteBundle:Projeto')
                        ->findOneBy(array('id' => $repertorios[$i]['projeto']));
                
                $umRepertorio->setProjeto($umProjeto);
                
                //$umArtista->setDataCadastro($artistas[$i]['data_cadastro']);

                //die(var_dump($umArtista));
                
                $em->persist($umRepertorio);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umRepertorio));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umRepertorio->setId($repertorios[$i]['id']);
                    $em->flush();
                }
                
            }
            
            // MUSICAS REPERTORIOS
            
            $musicasRepertorios = $dados['musicas_repertorios'];
            $total = count($musicasRepertorios);
            //$processadas = array();
            
            for($i = 0; $i < $total; $i++){
                
                $existe = false;
                $umMusicaRepertorio = null;
                
                $umMusicaRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                        ->findOneBy(array('id' => $musicasRepertorios[$i]['id']));
                
                if($umMusicaRepertorio == null){
                    $umMusicaRepertorio = new MusicaRepertorio();
                    $umMusicaRepertorio->setId($musicasRepertorios[$i]['id']);
                } else{
                    $existe = true;
                }
                
                $umMusicaRepertorio->setOrdem($musicasRepertorios[$i]['ordem']);
                $umMusicaRepertorio->setStatus($musicasRepertorios[$i]['status']);
                
                $umMusica = new Musica();
                $umMusica = $em->getRepository('RepSiteBundle:Musica')
                        ->findOneBy(array('id' => $musicasRepertorios[$i]['musica']));
                
                $umMusicaRepertorio->setMusica($umMusica);
                
                $umRepertorio = new Repertorio();
                $umRepertorio = $em->getRepository('RepSiteBundle:Repertorio')
                        ->findOneBy(array('id' => $musicasRepertorios[$i]['repertorio']));
                
                $umMusicaRepertorio->setRepertorio($umRepertorio);
                
                $em->persist($umMusicaRepertorio);
                
                if(!$existe){
                    $metadata = $em->getClassMetaData(get_class($umMusicaRepertorio));
                    $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
                    $metadata->setIdGenerator(new AssignedGenerator());
                    $umMusicaRepertorio->setId($musicasRepertorios[$i]['id']);
                    $em->flush();
                }
                
            }
            
            $em->flush();
            
            $view = View::create(
                    array(
                        "meta" => array(array("registros" => 0, "status" => 200, "mensagem" => "ok"))
                    ),
                200, array('totalRegistros' => 0))->setTemplateVar("u");
            
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
    
    public function validaTokenAction() {
        $em = $this->getDoctrine()->getManager();

        // Get $id_token via HTTPS POST.
        $CLIENT_ID = "763791909416-aim4u63mdttsktinjqroogqdndlibf7l.apps.googleusercontent.com";
        $idToken = $this->get('request')->request->get('idToken');
        
        $client = new \Google_Client(['client_id' => $CLIENT_ID]);
        $payload = $client->verifyIdToken($idToken);
        
        if ($payload) {
            $email = $payload['email'];
            $userid = $payload['sub'];
            
            $usuario = $em->getRepository('RepSiteBundle:Usuario')
                        ->findOneBy(array('email' => $email));
            
            if($usuario != null){
                
                if($usuario->getGoogleId() == null){
                    $usuario->setGoogleID($userid);
                    $em->persist($usuario);
                    $em->flush();
                }
                
                $view = View::create(
                    array(
                        "meta" => array(array("registros" => 0, "status" => 200, "mensagem" => "ok"))
                    ),
                    200, array('totalRegistros' => 0))->setTemplateVar("u");

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
}
