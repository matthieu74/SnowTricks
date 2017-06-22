<?php

namespace ST\FigureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use ST\FigureBundle\Entity\FigureHisto;

/**
 * Figure
 *
 * @ORM\Table(name="figure")
 * @ORM\Entity(repositoryClass="ST\FigureBundle\Repository\FigureRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Figure
{
	
	public function __construct()
    {
        $this->typeFigure = new ArrayCollection();
        $this->figureHisto = null;
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
	
	/**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
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
   * @ORM\ManyToOne(targetEntity="ST\UserBundle\Entity\User")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;
	
	 /**
     * @var \Doctrine\Common\Collections\Collection
     *
   * @ORM\ManyToMany(targetEntity="ST\FigureBundle\Entity\TypeFigure")
   */
  private $typeFigure;

	/**
     * @ORM\Column(name="active", type="integer")
     */
	private $active = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function setImage($image)
    {
        $this->image = $image;
    }
	public function setActive($active)
	{
		$this->active = $active;
	}
	
	public function getActive()
	{
		return $this->active;
	}
	
	public function addTypeFigure(TypeFigure $typeFigure)
    {
        if ($this->typeFigure->contains($typeFigure)) {
            return;
        }
        $this->typeFigure->add($typeFigure);
    }
    public function removeTypeFigure(TypeFigure $typeFigure)
    {
       if (!$this->typeFigure->contains($typeFigure)) {
            return;
        }
        $this->typeFigure->removeElement($typeFigure);
    }
	public function getTypeFigure()
	{
		return $this->typeFigure;
	}
	
	public function setTypeFigure($typeFigure)
	{
		$this->$typeFigure = $typeFigure;
	}
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return Figure
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Figure
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
	
	
	public function setUpdateDate(\Datetime $updateDate)
	{
		$this->updateDate = $updateDate;

		return $this;
	}

	public function getUpdateDate()
	{
		return $this->updateDate;
	}

	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getSlug()
	{
		return $this->slug;
	}	
    
    private function setId($id)
    {
        $this->id = $id;
    }
    
    private function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    public function setFromFigureHisto($figureHisto)
    {
        $this->setId($figureHisto->getIdFigure());
        $this->setName($figureHisto->getName());
        $this->setSlug($figureHisto->getSlug());
        $this->setDescription($figureHisto->getDescription());
        $this->setUpdateDate($figureHisto->getUpdateDate());
        $this->setImage($figureHisto->getImage());   
        $this->setUser($figureHisto->getUser());
    }
}
