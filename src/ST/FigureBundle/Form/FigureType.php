<?php

namespace ST\FigureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use ST\FigureBundle\Entity\Figure;
use ST\FigureBundle\Entity\TypeFigure;

class FigureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class, array('label' => 'Nom'))
					->add('description', CKEditorType::class, array('label' => 'Description'))
					->add('typeFigure', CollectionType::class, array(
							'label' => 'Groupes',
							'entry_type' => TypeFigureType::class,
							'allow_add' => true,
							'allow_delete' => true,
							)
						 );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
		$resolver->setDefaults(array(
            'data_class' => Figure::class,
        ));
    }

	
	public function getName() {
		return 'standalone';
	}


}
