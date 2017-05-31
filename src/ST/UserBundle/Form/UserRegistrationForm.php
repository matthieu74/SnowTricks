<?php
namespace ST\UserBundle\Form;
use ST\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class UserRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('username',TextType::class, array('label' => false,
											'attr' => array(
												'placeholder' => 'Username'
												
											)))
            ->add('plainPassword', RepeatedType::class, array('type' => PasswordType::class,
											'attr' => array(
												'placeholder' => 'Password'
												
											)));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Registration']
        ]);
    }
}