<?php

namespace ST\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use ST\AdminBundle\Form\FigureHistoRestoreType;

class AdminController extends Controller
{
    public function indexAction()
    {
    	
    	$figures = $this->get('admin_service')->getFigures();
    	$array = array(
    			'figures' => $figures
    	);
    	return $this->render('STAdminBundle:Default:index.html.twig', $array);
    }
    
    public function activateAction($id, $actValue)
    {
    	$figures = $this->get('admin_service')->setActive($id, $actValue);
    	return $this->redirectToRoute('st_admin_homepage');
    	
    }
    
    public function histoAction($id)
    {
        $histo = $this->get('admin_service')->getHisto($id);
        $array = array(
    			'histo' => $histo
    	);
    	return $this->render('STAdminBundle:Default:histo.html.twig', $array);
    }
    
    public function viewAction($id,$version, Request $request)
    {
        $figureHisto = $this->get('admin_service')->getVersion($version);
		$form = $this->get('form.factory')->create(FigureHistoRestoreType::class, $figureHisto);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$this->get('admin_service')->restoreFigure($figureHisto);			
			return $this->redirectToRoute('st_home');
		}
        return $this->render('STAdminBundle:Default:restore.html.twig', array(
								'figure' => $figureHisto,
								'form' => $form->createView(),
    							));
    }
}
