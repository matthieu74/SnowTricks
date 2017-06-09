<?php
namespace ST\UserBundle\Models\Service;
use Doctrine\ORM\EntityRepository;
use ST\UserBundle\Entity\User;


class UserService
{
	private $em;
	public function  __construct($em)
	{
		$this->em = $em;
	}
	
	public function addUser($user)
	{
		$this->em->persist($user);
        $this->em->flush();
	}
	
	
	public function getUserByName($name)
	{
		return $this->em ->getRepository('STUserBundle:User')->findOneBy(array('username' => $name));
	}
	
	public function deleteAllUsers()
	{
		$sql = 'DELETE FROM STUserBundle:User';
		$stmt = $this->em->createQuery($sql);
		$stmt->execute();
	}
}