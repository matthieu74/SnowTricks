<?php

// src/ST/FigureBundle/Controller/FigureController.php

namespace ST\FigureBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FigureController extends Controller
{
	
    public function indexAction()
    {
		return new Response('Welcome to the homepage.');
    }
}