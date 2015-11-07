<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Battle
 *
 * @ORM\Table("battles")
 * @ORM\Entity
 */
class Battle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\
     */

    /**
     * @var string
     *
     * @ORM\Column(name="userWeapon", type="string", length=50)
     */
    private $userWeapon;

    /**
     * @var string
     *
     * @ORM\Column(name="computerWeapon", type="string", length=50)
     */
    private $computerWeapon;

    /**
     * @var integer
     *
     * @ORM\Column(name="victor", type="smallint")
     */
    private $victor;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="battles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

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
     * Set userWeapon
     *
     * @param string $userWeapon
     *
     * @return Battle
     */
    public function setUserWeapon($userWeapon)
    {
        $this->userWeapon = $userWeapon;

        return $this;
    }

    /**
     * Get userWeapon
     *
     * @return string
     */
    public function getUserWeapon()
    {
        return $this->userWeapon;
    }

    /**
     * Set computerWeapon
     *
     * @param string $computerWeapon
     *
     * @return Battle
     */
    public function setComputerWeapon($computerWeapon)
    {
        $this->computerWeapon = $computerWeapon;

        return $this;
    }

    /**
     * Get computerWeapon
     *
     * @return string
     */
    public function getComputerWeapon()
    {
        return $this->computerWeapon;
    }

    /**
     * Set victor
     *
     * @param integer $victor
     *
     * @return Battle
     */
    public function setVictor($victor)
    {
        $this->victor = $victor;

        return $this;
    }

    /**
     * Get victor
     *
     * @return integer
     */
    public function getVictor()
    {
        return $this->victor;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Battle
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
