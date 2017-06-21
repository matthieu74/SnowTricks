<?php

namespace ST\FigureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Figure
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="ST\FigureBundle\Repository\CommentRepository")
 */
class Comment
{
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
     * @ORM\Column(name="description", type="text")
     */
    private $comment;
	
	
	
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
   * @ORM\ManyToOne(targetEntity="ST\FigureBundle\Entity\Figure")
   * @ORM\JoinColumn(nullable=false)
   */
  private $figure;
  
  
  
  
	public function getId()
    {
        return $this->id;
    }
	
	public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
	
	public function getComment()
    {
        return $this->comment;
    }
	
	public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }
	
	public function getUpdateDate()
    {
        return $this->updateDate;
    }
	
	public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }
	public function getUser()
    {
        return $this->user;
    }
	
	public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
	
	public function getFigure()
    {
        return $this->figure;
    }
	
	public function setFigure($figure)
    {
        $this->figure = $figure;

        return $this;
    }
   
}

