<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"username"},
 *     errorPath="username",
 *     message="This username has already been taken."
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string", length=64)
     * @Assert\Length(max = 64)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email address."
     * )
     * @Assert\Length(max = 60)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="Battle", mappedBy="user")
     */
    private $battles;

    // using bcrypt which salts internally
    public function __construct()
    {
        $this->isActive = true;
        $this->battles = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized);
    }

    /**
     * Add battle
     *
     * @param \AppBundle\Entity\Battle $battle
     *
     * @return User
     */
    public function addBattle(\AppBundle\Entity\Battle $battle)
    {
        $this->battles[] = $battle;

        return $this;
    }

    /**
     * Remove battle
     *
     * @param \AppBundle\Entity\Battle $battle
     */
    public function removeBattle(\AppBundle\Entity\Battle $battle)
    {
        $this->battles->removeElement($battle);
    }

    /**
     * Get battles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBattles()
    {
        return $this->battles;
    }
}
