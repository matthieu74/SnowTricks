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
        return $this->render(
            'STUserBundle:Security:login.html.twig',
            array(
                'form' => $this->createForm(LoginForm::class, [
                                'username' => $authenticationUtils->getLastUsername(),
                            ])->createView(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            )
        );
    }
   
    public function logoutAction()
    {
        throw new \Exception('this should not be reached!');
    }
}