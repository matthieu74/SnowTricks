<?php
namespace ST\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ST\UserBundle\Form\LoginForm;

class SecurityController extends Controller
{
    
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
            'username' => $lastUsername,
        ]);
    
        return $this->render(
            'STUserBundle:Security:login.html.twig',
            array(
                'form' => $form->createView(),
                'error' => $error,
            )
        );
    }
   
    public function logoutAction()
    {
        throw new \Exception('this should not be reached!');
    }
}