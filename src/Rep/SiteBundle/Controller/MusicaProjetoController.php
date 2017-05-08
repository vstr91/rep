<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rep\SiteBundle\Controller;

use Rep\SiteBundle\Entity\Musica;
use Rep\SiteBundle\Entity\MusicaProjeto;
use Rep\SiteBundle\Form\MusicaProjetoType;
use Rep\SiteBundle\Form\MusicaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ArtistaController
 *
 * @author Almir
 */
class MusicaProjetoController extends Controller {
    
    public function cadastrarAction($id_musica_projeto){
        $musicaProjeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $musicaProjeto = $em->find('RepSiteBundle:MusicaProjeto', $id_musica_projeto);
        
        //se nao existir, cria novo objeto
        if(is_null($musicaProjeto)){
            $musicaProjeto = new MusicaProjeto();
        }
        
        $form = $this->createForm(new MusicaProjetoType(), $musicaProjeto);
        $form->bind($request);
        
        if($form->isValid()){
            
            //cadastra ou edita objeto
            
            $em->persist($musicaProjeto);
            $em->flush();
            
            $referer = $request->headers->get('referer');
            
//            return $this->redirect($this->generateUrl('rep_site_musicas'));
            return $this->redirect($referer);
        }
        
        $musicas = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodas();//findAll();
        
        return $this->render('RepSiteBundle:Page:musicas-projetos.html.twig', 
                array(
                    'usuario' => $user,
                    'musicas' => $musicas,
                    'formMusicaProjeto' => $form->createView()
                ));
        
    }
    
    public function formAction($id_musica_projeto){
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        $musicaProjeto = $em->find('RepSiteBundle:MusicaProjeto', $id_musica_projeto);
        
        if(is_null($musicaProjeto)){
            $musicaProjeto = new MusicaProjeto();
        }
        
        $form = $this->createForm(new MusicaProjetoType(), $musicaProjeto);
        
//        $form->bind($request);
        
        return $this->render('RepSiteBundle:MusicaProjeto:form.html.twig',
                array(
                    'form' => $form->createView(),
                    'musicaProjeto' => $musicaProjeto
                ));
    }
    
    public function musicasProjetoAction($slug){
        $projeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->getRepository('RepSiteBundle:Projeto')->findOneBy(array('slug' => $slug));
        
        $musicasProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($slug);
        
        $musicas = $em->getRepository('RepSiteBundle:Musica')->findBy(array('status' => 0));
        
        $referer = $request->headers->get('referer');
        
        return $this->render('RepSiteBundle:MusicaEvento:musicas-projeto.html.twig', 
                array(
                    'usuario' => $user,
                    'projeto' => $projeto,
                    'musicasProjeto' => $musicasProjeto,
                    'musicasAtivas' => $musicas,
                    'referer' => $referer
                ));
        
    }
    
    function atualizaMusicasProjetoAction($id_projeto){
        
        $em = $this->getDoctrine()->getManager();
        
        $musicas = $this->get('request')->request->get('musicas');
        
        $em->getRepository('RepSiteBundle:MusicaProjeto')->invalidaTodasMusicasProjeto($id_projeto);
        
        $cont = 1;
        
        foreach($musicas as $musica){
            
            $dados = explode("|", $musica);
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $dados[0]);
            $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
            
            $musicaProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')->
                    findOneBy(array('musica' => $umaMusica, 'projeto' => $projeto));
            
            if($musicaProjeto == null){
                $musicaProjeto = new MusicaProjeto();
            }
            
            $musicaProjeto->setMusica($umaMusica);
            $musicaProjeto->setProjeto($projeto);
            
            $musicaProjeto->setStatus(0);
            
            $em->persist($musicaProjeto);
            
            $cont++;
            
        }
        
        $em->flush();
        
        return new Response();
        
    }
    
    public function formCadastraMusicaAction($id_projeto){
        $projeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        $musicasProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listaMusicasAtivasAusentesNoProjeto($id_projeto);
        
        if($musicasProjeto === null){
            $musicasProjeto = $em->getRepository('RepSiteBundle:Musica')->listarTodas();
        }
        
        return $this->render('RepSiteBundle:MusicaProjeto:adiciona-musica.html.twig', 
                array(
                    'usuario' => $user,
                    'projeto' => $projeto,
                    'musicas' => $musicasProjeto
                ));
        
    }
    
    public function cadastraMusicaAction($id_projeto){
        $projeto = null;
        $request = $this->getRequest();
        
        $musicas = $request->request->get('musica');
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        foreach($musicas as $musica){
            
            $umaMusica = $em->find('RepSiteBundle:Musica', $musica);
            
            $umaMusicaProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')->
                findOneBy(array('musica' => $umaMusica, 'projeto' => $projeto));
            
            if($umaMusicaProjeto == null){
                $umaMusicaProjeto = new MusicaProjeto();
            }
            
            $umaMusicaProjeto->setMusica($umaMusica);
            $umaMusicaProjeto->setProjeto($projeto);
            $umaMusicaProjeto->setStatus(0);
            
            $em->persist($umaMusicaProjeto);
            
            if($umaMusica->getStatus() != 0){
                $umaMusica->setStatus(0);
                $em->persist($umaMusica);
            }
            
        }
        
        $em->flush();
        
        $musicasProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($projeto->getSlug(), 0);
        
        $musicasProjetoFila = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($projeto->getSlug(), 1);
        
        return $this->redirect($this->generateUrl('rep_site_musicas_projetos', array('id_projeto' => $id_projeto, 
            'musicas' => $musicasProjeto, 'musicasProjetoFila' => $musicasProjetoFila, 'projeto' => $projeto->getSlug())));
        
    }
    
    public function carregaRepertorioAction($id_projeto, $status){
        $projeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        $musicasProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($projeto->getSlug(), $status);
        
        return $this->render('RepSiteBundle:MusicaProjeto:tabela-cadastrados.html.twig', 
                array(
                    'usuario' => $user,
                    'projeto' => $projeto,
                    'musicas' => $musicasProjeto
                ));
        
    }
    
    public function removeMusicaProjetoAction($id_projeto, $id_musica){
        $projeto = null;
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        $musica = $em->find('RepSiteBundle:Musica', $id_musica);
        
        $musicaProjeto = new MusicaProjeto();
        
        $musicaProjeto->setMusica($musica);
        $musicaProjeto->setProjeto($projeto);
        
        $musicaProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')->
                findOneBy(array('musica' => $musica, 'projeto' => $projeto));
        
        $musicaProjeto->setStatus(2);
        
        $em->persist($musicaProjeto);
        
        $em->flush();
        
        return new Response();
        
    }
    
    public function geraPdfAction($slug){
        $projeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->getRepository('RepSiteBundle:Projeto')->findOneBy(array('slug' => $slug));
        
        $musicasProjeto = $em->getRepository('RepSiteBundle:MusicaProjeto')
                ->listarTodasPorProjeto($projeto->getSlug());
        
        $pdf = new \FPDF();

        $pdf->AddFont('pfennigb','','pfennigb.php');
        
        $pdf->AddPage();
        $pdf->SetFont('pfennigb','',16);
        
        $pdf->Cell(0,25,"LOGO",0,1, 'C');
        $pdf->Cell(0,15,$projeto->getNome(),0,1, 'C');
        $pdf->Cell(0,15,$projeto->getData()->format('d/m/Y H:i:s'),0,1, 'C');
        
        $pdf->SetFont('pfennigb','',20);
        
        $pdf->SetY(70);
        
        $cont = 0;
        $x = 10.00125;
        $y = 70;
        $paginas = 1;
        
        foreach($musicasProjeto as $musica){
            
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
    
    public function formCadastraMusicaNovaAction($id_projeto){
        $projeto = null;
        $request = $this->getRequest();
        
        $request = $this->getRequest();
        
        $em = $this->getDoctrine()->getManager();
        
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        $musica = new Musica();
        
        $form = $this->createForm(new MusicaType(), $musica);

        return $this->render('RepSiteBundle:Musica:form-nova.html.twig',
                array(
                    'form' => $form->createView(),
                    'musica' => $musica,
                    'projeto' => $projeto
                ));
        
    }
    
    public function cadastrarNovaAction($id_projeto){
        $musica = null;
        $projeto = null;
        $request = $this->getRequest();
        
        $user = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        //verifica se ja existe registro
        $projeto = $em->find('RepSiteBundle:Projeto', $id_projeto);
        
        $musica = new Musica();
        
        $form = $this->createForm(new MusicaType(), $musica);
        $form->bind($request);
        
        if($form->isValid()){
            
            $em->persist($musica);
            
            $musicaProjeto = new MusicaProjeto();
            $musicaProjeto->setMusica($musica);
            $musicaProjeto->setProjeto($projeto);
            
            $em->persist($musicaProjeto);
            
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
    
}
