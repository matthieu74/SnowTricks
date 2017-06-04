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

		$paging = $this->get('layout_service')->getPaging($results,9, $offset);
		$array = array(
						'title' => 'snow tricks',
						'figures' => $results,
                        'paging' => $paging
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
		
		$form = $this->get('form.factory')->create(FigureType::class, $figure);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$typeFigureArray = [];
			if (array_key_exists('typeFigure',$_POST['figure']))
			{
				$typeFigureArray = $_POST['figure']['typeFigure'];
			}
			$results = $this->get('figure_service')->addFigure($this->container->get('security.token_storage')->getToken()->getUser(),
									  $figure, $typeFigureArray);
			if (!is_null($results))
			{
				$this->get('session')->getFlashBag()->add(
						'error',
						$results
				);
				return $this->render('STFigureBundle:Core:edit.html.twig', array(
									'form' => $form->createView(),
								));
			}
			$this->get('session')->getFlashBag()->add(
						'notice',
						'Votre figure a bien été ajouté'
			);
			return $this->redirectToRoute('st_home');
		}
		$categories = $this->get('figure_service')->getAllTypeFigure();
		return $this->render('STFigureBundle:Core:edit.html.twig', array(
      							'form' => $form->createView(), 'categories' => $categories,
    						));
	}
	
	/**
     * @Security("has_role('ROLE_USER')")
     */
	public function editFigureAction($id, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigureById($id);
		$form = $this->get('form.factory')->create(FigureType::class, $figure);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$typeFigureArray = [];
			if (array_key_exists('typeFigure',$_POST['figure']))
			{
				$typeFigureArray = $_POST['figure']['typeFigure'];
			}
			$this->get('figure_service')->saveFigure($figure, $typeFigureArray);
			$this->get('session')->getFlashBag()->add(
						'notice',
						'Votre figure a bien été modifié'
			);
			return $this->redirectToRoute('st_home');
		}
		$categories = $this->get('figure_service')->getAllTypeFigure();
		return $this->render('STFigureBundle:Core:edit.html.twig', array(
      							'form' => $form->createView(), 'categories' => $categories,
    		));
	}
	
	
	public function viewFigureBy10Action($name, $offset, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigure($name);
		if (null === $figure) {
		  	throw new NotFoundHttpException("La figure d'id ".$id." n'existe pas.");
		}
		
		$listComment = $this->get('figure_service')->getComments($figure, $offset);
		
		$listTypeFigure = $this->get('figure_service')->getTypesFigures($figure);
			
		$comment = new Comment();
		$form = $this->get('form.factory')->create(CommentType::class, $comment);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
		{
			$user = $this->container->get('security.token_storage')->getToken()->getUser();
			$this->get('figure_service')->saveComment($user,$figure, $comment);
			return $this->redirectToRoute('st_figure_view_by_10', array(
        												'name' => $name,
														'offset' => $offset));
		}
		
		
		
		$paging = $this->get('layout_service')->getPaging($listComment,10, $offset);
    	$array = array(
						'title' => 'snow tricks',
						'figure' => $figure,
						'comments' => $listComment,
						'types_figure' => $listTypeFigure,
						'form' => $form->createView(),
						'paging' => $paging
						);
		
		//return var_dump($listTypeFigure);
		return $this->render('STFigureBundle:Core:detail.html.twig', $array);
	}
		
	public function viewFigureAction($name, Request $request)
	{
		return $this->viewFigureBy10Action($name,0, $request);
	}	

	/**
     * @Security("has_role('ROLE_USER')")
     */
	public function deleteFigureAction($id, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigureById($id);
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