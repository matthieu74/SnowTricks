<?php

namespace ST\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('STAdminBundle:Default:index.html.twig');
    }
}
