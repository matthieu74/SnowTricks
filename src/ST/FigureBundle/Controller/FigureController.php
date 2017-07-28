<?php

// src/ST/FigureBundle/Controller/FigureController.php

namespace ST\FigureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use ST\FigureBundle\Form\FigureType;
use ST\FigureBundle\Form\FigureDeleteType;
use ST\FigureBundle\Form\CommentType;
use ST\FigureBundle\Entity\Figure;
use ST\FigureBundle\Entity\TypeFigure;
use ST\FigureBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FigureController extends Controller
{
	
    public function showFigureAction($offset)
    {
		$results = $this->get('figure_service')->getFigures($offset);

		return $this->render('STFigureBundle:Core:index.html.twig', array(
            'title' => 'snow tricks',
            'figures' => $results,
            'paging' => $this->get('layout_service')->getPaging($results,9, $offset)
        ));
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
            $postData = $request->request->get('figure');
            $typeFigureArray = [];
            if (array_key_exists('typeFigure',$postData))
            {
                $typeFigureArray = $postData['typeFigure'];
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

		return $this->render('STFigureBundle:Core:edit.html.twig', array(
      							'form' => $form->createView(),
                                'categories' => $this->get('figure_service')->getAllTypeFigure(),
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
            $postData = $request->request->get('figure');
			$typeFigureArray = [];
			if (array_key_exists('typeFigure',$postData))
			{
				$typeFigureArray = $postData['typeFigure'];
			}
			$this->get('figure_service')->saveFigure($figure, $typeFigureArray);
			$this->get('session')->getFlashBag()->add(
						'notice',
						'Votre figure a bien été modifié'
			);
			return $this->redirectToRoute('st_home');
		}

		return $this->render('STFigureBundle:Core:edit.html.twig', array(
      							'form' => $form->createView(),
                                'categories' => $this->get('figure_service')->getAllTypeFigure(),
    		));
	}
	
	
	public function viewFigureBy10Action($name, $offset, Request $request)
	{
    	$figure = $this->get('figure_service')->getFigure($name);
		if (null === $figure) {
		  	throw new NotFoundHttpException("La figure de nom ".$name." n'existe pas.");
		}
		


			
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

        $listComment = $this->get('figure_service')->getComments($figure, $offset);
		return $this->render('STFigureBundle:Core:detail.html.twig',
            array(
                'title' => 'snow tricks',
                'figure' => $figure,
                'comments' => $listComment,
                'types_figure' => $this->get('figure_service')->getTypesFigures($figure),
                'form' => $form->createView(),
                'paging' => $this->get('layout_service')->getPaging($listComment,10, $offset)
            ));
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