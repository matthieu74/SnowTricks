<?php
namespace ST\UserBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => false,
											'attr' => array(
												'placeholder' => 'Username'
												
											)))
            ->add('password', PasswordType::class, array(
											'attr' => array(
												'placeholder' => 'Password'
											)));
    }
}