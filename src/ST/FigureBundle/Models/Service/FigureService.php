<?php
namespace ST\FigureBundle\Models\Service;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use ST\FigureBundle\Entity\TypeFigure;
use Symfony\Component\DomCrawler\Crawler;


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
		$query->select('f.id,f.name,f.slug, f.updateDate,u.username, f.image')
			->from('ST\FigureBundle\Entity\Figure', 'f')
			->leftjoin('f.user', 'u')
			->where('f.active = 0')
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
			$nameCat = '';
			if (is_string($typeF))
			{
				$nameCat = $typeF;
			}
			else
			{
				if (array_key_exists('name', $typeF))
				{
					$nameCat = $typeF['name'];
				}
				else
				{
					$nameCat = $typeF;
				}
			}
			$typeFigure = $this->em->getRepository('STFigureBundle:TypeFigure')->findBy(array('name' => $nameCat));
			if (count($typeFigure) == 0)
			{
				$t = new TypeFigure();
				$t->setName($nameCat);
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
        $this->getImage($figure);
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
	
	public function getFigurebyName($name)
	{
		return $this->em ->getRepository('STFigureBundle:Figure')->findOneBy(array('name' => $name));
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
        
        $this->getImage($figure);
		$figure->setUpdateDate(new \DateTime());
		$this->em ->persist($figure);
		
		
		$this->em ->flush();
	}
	
    private function getImage($figure)
    {   
		try 
		{
			$doc = new \DOMDocument();
        	$doc->loadHTML($figure->getDescription());
		

			$tags = $doc->getElementsByTagName('img');

			foreach ($tags as $tag) 
			{
				$figure->setImage(substr($tag->getAttribute('src'), 1));
				return;
			}
		}
		catch (Exception $e) 
		{
			return;
		}
    }
    
	public function deleteFigure($figure)
	{
		$figure->setActive(1);
		$this->em ->persist($figure);
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
	
	public function addComment($comment)
	{
		$this->em->persist($comment);
		$this->em->flush();
	}
	
	public function getAllTypeFigure()
	{
		return $this->em->getRepository('STFigureBundle:TypeFigure')->findAll();
	}
	
	
	public function deleteAllData()
	{
		$sql = 'DELETE FROM STFigureBundle:Comment';
		$stmt = $this->em->createQuery($sql);
		$stmt->execute();
		
		
		$sql = 'DELETE FROM STFigureBundle:Figure';
		$stmt = $this->em->createQuery($sql);
		$stmt->execute();
		
		$sql = 'DELETE FROM STFigureBundle:Typefigure';
		$stmt = $this->em->createQuery($sql);
		$stmt->execute();
	}
}