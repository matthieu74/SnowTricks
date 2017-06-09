<?php
namespace ST\UserBundle\Controller;


use ST\UserBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
			$user->upload();
           
            $this->get('user_service')->addUser($user);
            return $this->redirectToRoute('st_home');
        }
        return $this->render('STUserBundle:User:register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}