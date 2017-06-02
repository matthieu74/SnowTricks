<?php
namespace ST\FigureBundle\Models\Service;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use ST\FigureBundle\Entity\TypeFigure;

class FigureService
{
	private $em;
	public function  __construct($em)
	{
		$this->em = $em;
	}
	
	public function getFigures($offset)
	{
		$firstRow = intval($offset) * 9;
		
		$query = $this->em->createQueryBuilder();
		$query->select('f.id,f.name,f.slug, f.updateDate,u.username')
			->from('ST\FigureBundle\Entity\Figure', 'f')
			->leftjoin('f.user', 'u')
			->addOrderBy('f.updateDate', 'DESC')
			 ->setMaxResults(9)
            ->setFirstResult($firstRow);

		return $query->getQuery()->getResult();
	}
	
	public function addFigure($user, $figure, $listTypeFigure)
	{
		$figureWithSameName = $this->em->getRepository('STFigureBundle:Figure')->findBy(array('name' => $figure->getName()));
		if (count($figureWithSameName) > 0)
		{
			return 'Ce nom de figure est déjà utilisé';
		}
		foreach($figure->getTypeFigure() as $typeF)
        {
			$figure->removeTypeFigure($typeF);			
        }
		
		foreach($listTypeFigure as $typeF)
        {
			$typeFigure = $this->em->getRepository('STFigureBundle:TypeFigure')->findBy(array('name' => $typeF['name']));
			if (count($typeFigure) == 0)
			{
				$t = new TypeFigure();
				$t->setName($typeF['name']);
          		$this->em->persist($t);
				$this->em->flush();
				$figure->addTypeFigure($t);
			}
			else
			{
				foreach($typeFigure as $f)
        		{
					$figure->addTypeFigure($f);
					$this->em->persist($f);
				}
			}
			
        }
		$figure->setUser($user);
		$figure->setUpdateDate(new \DateTime());
		$this->em ->persist($figure);
		$this->em ->flush();
		return null;
	}
	
	public function getFigure($slug)
	{
		return $this->em ->getRepository('STFigureBundle:Figure')->findOneBy(array('slug' => $slug));
	}
	
	public function getFigureById($id)
	{
		return $this->em ->getRepository('STFigureBundle:Figure')->find($id);
	}
	
	public function saveFigure($figure, $listTypeFigure)
	{
		foreach($figure->getTypeFigure() as $typeF)
        {
			$figure->removeTypeFigure($typeF);			
        }
		foreach($listTypeFigure as $typeF)
        {
			$typeFigure = $this->em->getRepository('STFigureBundle:TypeFigure')->findBy(array('name' => $typeF['name']));
			if (count($typeFigure) == 0)
			{
				$t = new TypeFigure();
				$t->setName($typeF['name']);
          		$this->em->persist($t);
				$this->em->flush();
				$figure->addTypeFigure($t);
			}
			else
			{
				foreach($typeFigure as $f)
        		{
					$figure->addTypeFigure($f);
					$this->em->persist($f);
				}
			}
			
        }
		
		$figure->setUpdateDate(new \DateTime());
		$this->em ->persist($figure);
		
		
		$this->em ->flush();
	}
	
	public function deleteFigure($figure)
	{
		$this->em->remove($figure);
		$this->em->flush();
	}
	
	public function getComments($figure, $offset)
	{
		$startOffset = $offset * 10;
		return $this->em->getRepository('STFigureBundle:Comment')
		 			->findBy(array('figure' => $figure), array('updateDate' => 'DESC'), 10, $startOffset);
		
	}
	
	public function getTypesFigures($figure)
	{
		$sql = 'SELECT name FROM figure_type_figure, typefigure where type_figure_id = id and figure_id = :p';
		$params['p'] = $figure->getId();
		$stmt = $this->em->getConnection()->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetchAll();
	}
	
	public function saveComment($user,$figure,  $comment)
	{
		$comment->setUser($user);
		$comment->setFigure($figure);
		$comment->setUpdateDate(new \DateTime());
		$this->em->persist($comment);
		$this->em->flush();
	}
	
	public function getAllTypeFigure()
	{
		return $this->em->getRepository('STFigureBundle:TypeFigure')->findAll();
	}
}