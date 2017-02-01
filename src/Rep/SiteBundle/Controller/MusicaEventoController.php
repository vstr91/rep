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
 * Description of ArtistaController
 *
 * @author Almir
 */
class MusicaEventoController extends Controller {
    
    public function musicasEventoAction($slug){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->getRepository('RepSiteBundle:Evento')->findOneBy(array('slug' => $slug));
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($slug);
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')->findBy(array('status' => 0));
        
        $referer = $request->headers->get('referer');
        $comentarios = $em->getRepository('RepSiteBundle:ComentarioEvento')
                ->listarTodosPorEvento($slug);
        
        $this->corrigirOrdem($evento);
        
        return $this->render('RepSiteBundle:MusicaEvento:musicas-evento.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicasEvento' => $musicasEvento,
                    'musicasAtivas' => $musicas,
                    'comentarios' => $comentarios,
                    'referer' => $referer
                ));
        
    }
    
    function atualizaMusicasEventoAction($id_evento){
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $this->get('request')->request->get('musicas');
        
        $em->getRepository('RepSiteBundle:MusicaEvento')->invalidaTodasMusicasEvento($id_evento);
        
        $cont = 1;
        
        foreach($musicas as $musica){
            
            $dados = explode("|", $musica);
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $dados[0]);
            $evento = $em->find('RepSiteBundle:Evento', $id_evento);
            
            $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                    findOneBy(array('musica' => $umaMusica, 'evento' => $evento));
            
            if($musicaEvento == null){
                $musicaEvento = new MusicaEvento();
            }
            
            $musicaEvento->setMusica($umaMusica);
            $musicaEvento->setEvento($evento);
            
            $musicaEvento->setStatus(0);
            $musicaEvento->setOrdem($cont);
            $musicaEvento->setObservacao($dados[1]);
            
            $em->persist($musicaEvento);
            
            $cont++;
            
        }
        
        $em->flush();
        
        return new Response();
        
    }
    
    public function formCadastraMusicaAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listaMusicasAtivasAusentesNoEvento($id_evento);
        
        if(sizeof($musicasEvento) == 0){
            $musicasEvento = $em->getRepository('RepSiteBundle:Musica')->listarTodasAtivas();
        }
        
        return $this->render('RepSiteBundle:MusicaEvento:adiciona-musica.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicas' => $musicasEvento
                ));
        
    }
    
    public function cadastraMusicaAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $total = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->contarTodasPorEvento($evento->getSlug());
        
        $total = $total+1;
        
        $this->corrigirOrdem($evento);
        
        foreach($musicas as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica);
            
            $umaMusicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                findOneBy(array('musica' => $umaMusica, 'evento' => $evento));
            
            if($umaMusicaEvento == null){
                $umaMusicaEvento = new MusicaEvento();
            }
            
            $umaMusicaEvento->setMusica($umaMusica);
            $umaMusicaEvento->setEvento($evento);
            $umaMusicaEvento->setStatus(0);
            $umaMusicaEvento->setOrdem($total);
            
            $em->persist($umaMusicaEvento);
            
            if($umaMusica->getStatus() != 0){
                $umaMusica->setStatus(0);
                $em->persist($umaMusica);
            }
            
            $total++;
            
        }
        
        $em->flush();
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($evento->getSlug());
        
        return $this->redirect($this->generateUrl('rep_site_musicas_evento', array('id_evento' => $id_evento, 
            'musicas' => $musicasEvento, 'slug' => $evento->getSlug())));
        
    }
    
    public function carregaRepertorioAction($id_evento){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($evento->getSlug());
        
        return $this->render('RepSiteBundle:MusicaEvento:tabela-repertorio.html.twig', 
                array(
                    'usuario' => $user,
                    'evento' => $evento,
                    'musicas' => $musicasEvento
                ));
        
    }
    
    public function removeMusicaEventoAction($id_evento, $id_musica){
        $evento = null;
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        $musica = $em->find('RepSiteBundle:Musica', $id_musica);
        
        $musicaEvento = new MusicaEvento();
        
        $musicaEvento->setMusica($musica);
        $musicaEvento->setEvento($evento);
        
        $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                findOneBy(array('musica' => $musica, 'evento' => $evento));
        
        $musicaEvento->setStatus(2);
        
        $em->persist($musicaEvento);
        
        $em->flush();
        
        return new Response();
        
    }
    
    function ordemMusicaEventoAction($id_evento){
        
        $em = $this->getDoctrine()->getManager();
        
        $ordem = $this->get('request')->request->get('ordem');
        
        //die(var_dump($ordem));
        
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);
        
        foreach($ordem as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica['id']);
            
            $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->
                    findOneBy(array('musica' => $umaMusica, 'evento' => $evento));
            
            if($musicaEvento == null){
                $musicaEvento = new MusicaEvento();
            }
            
            $musicaEvento->setMusica($umaMusica);
            $musicaEvento->setEvento($evento);
            
            $musicaEvento->setStatus(0);
            $musicaEvento->setOrdem($musica['ordem']);
            
            $em->persist($musicaEvento);
            
        }
        
        $em->flush();
        
        return new Response('ok');
        
    }
    
    public function cadastraIntervaloAction($id_evento){
        
        $evento = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        $evento = $em->find('RepSiteBundle:Evento', $id_evento);

        $umaMusicaEvento = new MusicaEvento();
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
            ->listarTodasPorEvento($id_evento);

        $total = count($musicasEvento)+1;

        $umaMusicaEvento->setMusica(null);
        $umaMusicaEvento->setEvento($evento);
        $umaMusicaEvento->setStatus(0);
        $umaMusicaEvento->setOrdem($total);
        
        $em->persist($umaMusicaEvento);
        $em->flush();
        
        return new Response('ok');
        
    }
    
    public function geraPdfAction($slug){
        $evento = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $evento = $em->getRepository('RepSiteBundle:Evento')->findOneBy(array('slug' => $slug));
        
        $musicasEvento = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($evento->getSlug());
        
        $pdf = new \FPDF();

        $pdf->AddFont('pfennigb','','pfennigb.php');
        
        $pdf->AddPage();
        $pdf->SetFont('pfennigb','',16);
        
        $pdf->Cell(0,25,"LOGO",0,1, 'C');
        $pdf->Cell(0,15,$evento->getNome(),0,1, 'C');
        $pdf->Cell(0,15,$evento->getData()->format('d/m/Y H:i:s'),0,1, 'C');
        
        $pdf->SetFont('pfennigb','',20);
        
        $pdf->SetY(70);
        
        $cont = 0;
        $x = 10.00125;
        $y = 70;
        $paginas = 1;
        
        foreach($musicasEvento as $musica){
            
            if($cont % 14 == 0 && $cont > 0){
                
                if($x == 10.00125){
                    $x = 100;
                    
                    if($paginas > 1){
                        $y = 20;
                    } else{
                        $y = 70;
                    }
                    
                    
                } else{
                    $pdf->AddPage();
                    $x = 10.00125;
                    $y = 20;
                    $paginas++;
                }
                
                
            }
            
            $pdf->SetXY($x, $y);
            $y = $y + 15;
            
//            if($cont > 15){
//                $pdf->SetX(100);
//            }
            
            $pdf->MultiCell(98,7,($cont+1)." - ".mb_strtoupper($musica->getNome(), 'utf-8')." (".$musica->getTom().")",0,1);
            $cont++;
        }
        
        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
        
    }
    
    function corrigirOrdem(\Rep\SiteBundle\Entity\Evento $evento){
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $em->getRepository('RepSiteBundle:MusicaEvento')
                ->listarTodasPorEvento($evento->getSlug());
        
        $total = count($musicas);
        
        for($i = 0; $i < $total; $i++){
            $musica = $musicas[$i];
            $musicaEvento = $em->getRepository('RepSiteBundle:MusicaEvento')->findOneBy(array('musica' => $musica, 'evento' => $evento));
            
            $musicaEvento->setOrdem($i+1);
            
            $em->persist($musicaEvento);
        }
        
        $em->flush();
        
    }
    
}
