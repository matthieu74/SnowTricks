<?php


namespace ST\FigureBundle\EventListener;

use ST\FigureBundle\Entity\Figure;
use ST\FigureBundle\Entity\FigureHisto;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Common\EventSubscriber;

class FigureEntityListener
{
	private $figureHisto = null;
	

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof Figure) {
            $entity->setUpdateDate(new \DateTime());
			$this->figureHisto = new FigureHisto();
			$this->figureHisto->hydrate($entity);		
		}
    }
	public function preUpdate(LifecycleEventArgs $args)
	{
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof Figure) {
            $entity->setUpdateDate(new \DateTime());
			$f = $em ->getRepository('STFigureBundle:Figure')->find($entity->getId());
			$this->figureHisto = new FigureHisto();
			$this->figureHisto->hydrate($f);		
		}
    }
		
	public function postFlush(PostFlushEventArgs $args)
	{
		if (! empty($this->figureHisto)) {
			
			$em = $args->getEntityManager();
			
			$em->persist($this->figureHisto);
						
			$this->figureHisto= null;
			$em->flush();
		}
	}
}