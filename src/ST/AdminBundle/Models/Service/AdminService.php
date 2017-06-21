<?php
namespace ST\AdminBundle\Models\Service;

use ST\FigureBundle\Entity\Figure;

class AdminService
{
	private $em;
	public function  __construct($em)
	{
		$this->em = $em;
	}
	
	public function getFigures()
	{
		$query = $this->em->createQueryBuilder();
		$query->select('f.id,f.name,f.slug,f.active, f.updateDate,u.username')
		->from('ST\FigureBundle\Entity\Figure', 'f')
		->leftjoin('f.user', 'u')
		->addOrderBy('f.updateDate', 'DESC');
		
		return $query->getQuery()->getResult();
	}
	
	public function setActive($idFigure, $actValue)
	{
		$figure = $this->em ->getRepository('STFigureBundle:Figure')->find($idFigure);
		$figure->setActive($actValue);
		$this->em ->persist($figure);
		$this->em ->flush();
	}

    public function getHisto($id)
    {
        return $this->em ->getRepository('STFigureBundle:FigureHisto')->findBy(
                        array('idFigure' => $id), array('updateDateHisto' => 'DESC'));
    }
    
    public function getVersion($version)
    {
        return $this->em ->getRepository('STFigureBundle:FigureHisto')->find($version);
    }
    
    public function restoreFigure($figureHisto)
    {
        $figure = $this->em ->getRepository('STFigureBundle:Figure')->find($figureHisto->getIdFigure());
        $figure->setFromFigureHisto($figureHisto);
        $this->em->persist($figure);
        $this->em->flush();
        return;
    }
}