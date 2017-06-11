<?php
namespace ST\UserBundle\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"username"}, message="It looks like your already have an account!")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
   	
	/**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", unique=true)
     */
	private $username;
	
    /**
     * The encoded password
     *
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * A non-persisted field that's used to create the encoded password.
     * @Assert\NotBlank(groups={"Registration"})
     *
     * @var string
     */
    private $plainPassword;
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];
	
	/**
	* @ORM\Column(name="url", type="string", length=255, nullable=true)
	*/
	private $url;

	/**
	* @ORM\Column(name="alt", type="string", length=255, nullable=true)
	*/
	private $alt;

	private $file;

	public function getFile()
	{
		return $this->file;
	}

	public function setFile(UploadedFile $file = null)
	{
		$this->file = $file;
	}
	
	public function getUrl()
	{
		return $this->url;
	}
	
	public function setAlt($alt)
	{
		$this->alt = $alt;
	}
	
	public function setUrl($url)
	{
		$this->url = $url;
	}
	
    // needed by the security system
    public function getUsername()
    {
        return $this->username;
    }
	public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getRoles()
    {
        $roles = $this->roles;
        // give everyone ROLE_USER!
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getSalt()
    {
        // leaving blank - I don't need/have a password!
    }
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }
  
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }
	

	public function upload()
	{
		if (null === $this->file) {
		  return;
		}

		$name = $this->file->getClientOriginalName();
		$url = uniqid() . $name;
		
		$this->file->move($this->getUploadRootDir(), $url);

		$this->url = $url;

		$this->alt = $name;
	}

	public function getUploadDir()
	{
		return 'uploads/img';
	}

	protected function getUploadRootDir()
	{
		return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}
}