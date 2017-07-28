<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\MusicaRepertorio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class MusicaRepertorioController extends Controller {
    
    public function musicasRepertorioAction($slug){
        $repertorio = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->getRepository('RepSiteBundle:Repertorio')->findOneBy(array('slug' => $slug));
        
        $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->listarTodasPorRepertorio($slug);
        
        $musicas = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($repertorio->getProjeto()->getSlug(), null);
        
        $referer = $request->headers->get('referer');
        
        $this->corrigirOrdem($repertorio);
        
        return $this->render('RepSiteBundle:MusicaRepertorio:musicas-repertorio.html.twig', 
                array(
                    'usuario' => $user,
                    'repertorio' => $repertorio,
                    'musicasRepertorio' => $musicasRepertorio,
                    'musicasAtivas' => $musicas,
                    'referer' => $referer
                ));
        
    }
    
    function atualizaMusicasRepertorioAction($id_repertorio){
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $this->get('request')->request->get('musicas');
        
        $em->getRepository('RepSiteBundle:MusicaRepertorio')->invalidaTodasMusicasRepertorio($id_repertorio);
        
        $cont = 1;
        
        foreach($musicas as $musica){
            
            $dados = explode("|", $musica);
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $dados[0]);
            $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
            
            $musicaRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')->
                    findOneBy(array('musica' => $umaMusica, 'repertorio' => $repertorio));
            
            if($musicaRepertorio == null){
                $musicaRepertorio = new MusicaRepertorio();
            }
            
            $musicaRepertorio->setMusica($umaMusica);
            $musicaRepertorio->setRepertorio($repertorio);
            
            $musicaRepertorio->setStatus(0);
            $musicaRepertorio->setOrdem($cont);
            $musicaRepertorio->setObservacao($dados[1]);
            
            $em->persist($musicaRepertorio);
            
            $cont++;
            
        }
        
        $em->flush();
        
        return new Response();
        
    }
    
    public function formCadastraMusicaAction($id_repertorio){
        $repertorio = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        
        $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->listaMusicasAtivasAusentesNoRepertorio($id_repertorio, $repertorio->getProjeto()->getId());
        
        if($musicasRepertorio === null){
            $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjetoRetornaMusica($repertorio->getProjeto()->getSlug(), null);
        }
        
        return $this->render('RepSiteBundle:MusicaRepertorio:adiciona-musica.html.twig', 
                array(
                    'usuario' => $user,
                    'repertorio' => $repertorio,
                    'musicas' => $musicasRepertorio
                ));
        
    }
    
    public function cadastraMusicaAction($id_repertorio){
        $repertorio = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        
        $total = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->contarTodasPorRepertorio($repertorio->getSlug());
        
        $total = $total+1;
        
        $this->corrigirOrdem($repertorio);
        
        foreach($musicas as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica);
            
            $umaMusicaRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')->
                findOneBy(array('musica' => $umaMusica, 'repertorio' => $repertorio));
            
            if($umaMusicaRepertorio == null){
                $umaMusicaRepertorio = new MusicaRepertorio();
            }
            
            $umaMusicaRepertorio->setMusica($umaMusica);
            $umaMusicaRepertorio->setRepertorio($repertorio);
            $umaMusicaRepertorio->setStatus(0);
            $umaMusicaRepertorio->setOrdem($total);
            
            $em->persist($umaMusicaRepertorio);
            
            if($umaMusica->getStatus() != 0){
                $umaMusica->setStatus(0);
                $em->persist($umaMusica);
            }
            
            $musicaProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                    ->findOneBy(array('musica' => $umaMusica, 'projeto' => $repertorio->getProjeto()));
            
            $total++;
            
        }
        
        $em->flush();
        
        $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->listarTodasPorRepertorio($repertorio->getSlug());
        
        return $this->redirect($this->generateUrl('rep_site_musicas_repertorio', array('id_repertorio' => $id_repertorio, 
            'musicas' => $musicasRepertorio, 'slug' => $repertorio->getSlug())));
        
    }
    
    public function carregaRepertorioAction($id_repertorio){
        $repertorio = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        
        $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->listarTodasPorRepertorio($repertorio->getSlug());
        
        return $this->render('RepSiteBundle:MusicaRepertorio:tabela-repertorio.html.twig', 
                array(
                    'usuario' => $user,
                    'repertorio' => $repertorio,
                    'musicas' => $musicasRepertorio
                ));
        
    }
    
    public function removeMusicaRepertorioAction($id_repertorio, $id_musica){
        $repertorio = null;
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        
        $musica = $em->find('RepSiteBundle:Musica', $id_musica);
        
        $musicaRepertorio = new MusicaRepertorio();
        
        $musicaRepertorio->setMusica($musica);
        $musicaRepertorio->setRepertorio($repertorio);
        
        $musicaRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')->
                findOneBy(array('musica' => $musica, 'repertorio' => $repertorio));
        
        $musicaRepertorio->setStatus(2);
        
        $em->persist($musicaRepertorio);
        
        $em->flush();
        
        return new Response();
        
    }
    
    function ordemMusicaRepertorioAction($id_repertorio){
        
        $em = $this->getDoctrine()->getManager();
        
        $ordem = $this->get('request')->request->get('ordem');
        
        //die(var_dump($ordem));
        
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);
        
        foreach($ordem as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica['id']);
            
            $musicaRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')->
                    findOneBy(array('musica' => $umaMusica, 'repertorio' => $repertorio));
            
            if($musicaRepertorio == null){
                $musicaRepertorio = new MusicaRepertorio();
            }
            
            $musicaRepertorio->setMusica($umaMusica);
            $musicaRepertorio->setRepertorio($repertorio);
            
            $musicaRepertorio->setStatus(0);
            $musicaRepertorio->setOrdem($musica['ordem']);
            
            $em->persist($musicaRepertorio);
            
        }
        
        $em->flush();
        
        return new Response('ok');
        
    }
    
    public function cadastraIntervaloAction($id_repertorio){
        
        $repertorio = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        $repertorio = $em->find('RepSiteBundle:Repertorio', $id_repertorio);

        $umaMusicaRepertorio = new MusicaRepertorio();
        
        $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
            ->listarTodasPorRepertorio($id_repertorio);

        $total = count($musicasRepertorio)+1;

        $umaMusicaRepertorio->setMusica(null);
        $umaMusicaRepertorio->setRepertorio($repertorio);
        $umaMusicaRepertorio->setStatus(0);
        $umaMusicaRepertorio->setOrdem($total);
        
        $em->persist($umaMusicaRepertorio);
        $em->flush();
        
        return new Response('ok');
        
    }
    
    public function geraPdfAction($slug){
        $repertorio = null;
        $request = $this->getRequest();
        
        $A4_HEIGHT = 297;
        $A4_WIDTH = 210;
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $repertorio = $em->getRepository('RepSiteBundle:Repertorio')->findOneBy(array('slug' => $slug));
        
        $musicasRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->listarTodasPorRepertorio($repertorio->getSlug());
        
        $logo = $this->get('kernel')->getRootDir() . '/../web/imagens/logo.png';
        
        $pdf = new \FPDF();

//        $pdf->AddFont('pfennigb','','pfennigb.php');
        
        $pdf->AddPage();
        $pdf->SetFont('arial','',16);
        
        $pdf->Cell(0,15,$pdf->Image($logo, 225 / 2 - 105 / 3, 3, 55),0,1, 'C');
        $pdf->Cell(0,15,$repertorio->getNome(),0,1, 'C');
        $pdf->Cell(0,15,$repertorio->getProjeto()->getNome(),0,1, 'C');
        
        $pdf->SetFont('arial','',20);
        
        $pdf->SetY(70);
        
        $cont = 0;
        $x = 10.00125;
        $y = 70;
        $paginas = 1;
        
        foreach($musicasRepertorio as $musica){
            
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
            
            if($musica->getTom()){
                $pdf->MultiCell(98,7,($cont+1)." - ".mb_strtoupper($musica->getNome(), 'utf-8')." (".$musica->getTom().")",0,1);
            } else{
                $pdf->MultiCell(98,7,($cont+1)." - ".mb_strtoupper($musica->getNome(), 'utf-8'),0,1);
            }
            
            
            $cont++;
        }
        
        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
        
    }
    
    function corrigirOrdem(\Rep\SiteBundle\Entity\Repertorio $repertorio){
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $em->getRepository('RepSiteBundle:MusicaRepertorio')
                ->listarTodasPorRepertorio($repertorio->getSlug());
        
        $total = count($musicas);
        
        for($i = 0; $i < $total; $i++){
            $musica = $musicas[$i];
            $musicaRepertorio = $em->getRepository('RepSiteBundle:MusicaRepertorio')->findOneBy(array('musica' => $musica, 'repertorio' => $repertorio));
            
            $musicaRepertorio->setOrdem($i+1);
            
            $em->persist($musicaRepertorio);
        }
        
        $em->flush();
        
    }
    
}
