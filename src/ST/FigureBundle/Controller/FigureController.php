<?php

// src/ST/FigureBundle/Controller/FigureController.php

namespace ST\FigureBundle\Controller;

use ST\FigureBundle\Form\FigureType;
use ST\FigureBundle\Form\FigureDeleteType;
use ST\FigureBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ST\FigureBundle\Entity\Figure;
use ST\FigureBundle\Entity\TypeFigure;
use ST\FigureBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FigureController extends Controller
{
	
    public function showFigureAction($offset)
    {
		$results = $this->get('figure_service')->getFigures($offset);

		$oldestDisable = '';
        $newestDisable = '';
        if ($offset == 0)
        {
            $newestDisable = 'not-active';   
        }
        
        if (count($results) < 5) 
        {
            $oldestDisable = 'not-active';
        }
        $oldestOffset = $offset + 1;
        $newestOffset = $offset - 1;
		$array = array(
						'title' => 'snow tricks',
						'figures' => $results,
                        'oldestOffset' => $oldestOffset,
                        'newestOffset' => $newestOffset,
                        'oldestDisable' => $oldestDisable,
                        'newestDisable' => $newestDisable
						);
		return $this->render('STFigureBundle:Core:index.html.twig', $array);
    }
	
	public function indexAction()
	{
		return $this->showFigureAction(0);
	}
    
	/**
     * @Security("has_role('ROLE_USER')")
     */
    public function addFigureAction(Request $request)
	{
		$figure = new Figure();
		$typeF = new TypeFigure();
		$typeF->setName('tit');
		$figure->getTypeFigure()->add($typeF);
		$form = $this->get('form.factory')->create(FigureType::class, $figure);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$results = $this->get('figure_service')->addFigure($this->container->get('security.token_storage')->getToken()->getUser(),
									  $figure);			
			return $this->redirectToRoute('st_home');
		}
		
		return $this->render('STFigureBundle:Core:edit.html.twig', array(
      							'form' => $form->createView(),
    						));
	}
	
	/**
     * @Security("has_role('ROLE_USER')")
     */
	public function editFigureAction($id, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigure($id);
		$form = $this->get('form.factory')->create(FigureType::class, $figure);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$this->get('figure_service')->saveFigure($figure);
			return $this->redirectToRoute('st_home');
		}
		
		return $this->render('STFigureBundle:Core:edit.html.twig', array(
      							'form' => $form->createView(),
    		));
	}
	
	
	public function viewFigureBy10Action($id, $offset, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigure($id);
		
		if (null === $figure) {
		  	throw new NotFoundHttpException("La figure d'id ".$id." n'existe pas.");
		}

		$listComment = $this->get('figure_service')->getComments($figure, $offset);
			
		$comment = new Comment();
		$form = $this->get('form.factory')->create(CommentType::class, $comment);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$user = $this->container->get('security.token_storage')->getToken()->getUser();
			$this->get('figure_service')->saveComment($user,$figure, $comment);
			return $this->redirectToRoute('st_figure_view_by_10', array(
        												'id' => $id,
														'offset' => $offset));
		}
		
		$oldestDisable = '';
        $newestDisable = '';
        if ($offset == 0)
        {
            $newestDisable = 'not-active';   
        }
        
        if (count($listComment) < 10) 
        {
            $oldestDisable = 'not-active';
        }
        $oldestOffset = $offset + 1;
        $newestOffset = $offset - 1;
    	$array = array(
						'title' => 'snow tricks',
						'figure' => $figure,
						'comments' => $listComment,
						'form' => $form->createView(),
						'oldestOffset' => $oldestOffset,
                        'newestOffset' => $newestOffset,
                        'oldestDisable' => $oldestDisable,
                        'newestDisable' => $newestDisable
						);
		return $this->render('STFigureBundle:Core:detail.html.twig', $array);
	}
	
	
	public function viewFigureAction($id, Request $request)
	{
		return $this->viewFigureBy10Action($id,0, $request);
	}	

	/**
     * @Security("has_role('ROLE_USER')")
     */
	public function deleteFigureAction($id, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigure($id);
		$form = $this->get('form.factory')->create(FigureDeleteType::class, $figure);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$this->get('figure_service')->deleteFigure($figure);			
			return $this->redirectToRoute('st_home');
		}
		
		return $this->render('STFigureBundle:Core:delete.html.twig', array(
								'title' => 'snow tricks',
								'figure' => $figure,
								'form' => $form->createView(),
    							));
	}
	
	
	public function show404Action()
	{
		return $this->render('STFigureBundle:Core:404.html.twig', array(
								'title' => 'snow tricks',
    							));
	}
	
}