<?php
namespace ST\UserBundle\Security;

use ST\UserBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;
    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }
    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->isMethod('POST');
        if (!$isLoginSubmit) {
            // skip authentication
            return;
        }
        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['username']
        );
        return $data;
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];
		
        return $this->em->getRepository('STUserBundle:User')
            ->findOneBy(['username' => $username]);
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['password'];
        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }
        return false;
    }
    protected function getLoginUrl()
    {
        return $this->router->generate('st_login');
    }
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('st_home');
    }
}