<?php

namespace ST\FigureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Figure
 *
 * @ORM\Table(name="figure_histo")
 * @ORM\Entity(repositoryClass="ST\FigureBundle\Repository\FigureHistoRepository")
 */
class FigureHisto
{
	
	public function __construct()
	{
	}
	
	
	public function hydrate(Figure $f)
	{
		$this->idFigure = $f->getId();
		$this->name = $f->getName();
		$this->slug = $f->getSlug();
		$this->description = $f->getDescription();
        $this->image = $f->getImage();
		$this->user = $f->getUser();
		$this->updateDate = $f->getUpdateDate();
		$this->updateDateHisto = new \DateTime();
	}
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var int
	 *
	 * @ORM\Column(name="idfigure", type="integer")
	 */
	private $idFigure;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;
	
	/**
	 * @Gedmo\Slug(fields={"name"})
	 * @ORM\Column(length=255)
	 */
	private $slug;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text")
	 */
	private $description;
	
	/**
	 * @ORM\Column(name="updatedate", type="datetime")
	 */
	private $updateDate;
	
	/**
	 * @ORM\Column(name="updatedatehisto", type="datetime")
	 */
	private $updateDateHisto;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ST\UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;
	
	
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="image", type="string", length=255, nullable=true)
	 */
	private $image;
	
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function getIdFigure()
    {
        return $this->idFigure;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getDescription()
    {
        return  $this->description;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function getUpdateDateHisto()
    {
        return $this->updateDateHisto;
    }
}

