<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Nataniel\BoardGameGeek\Thing;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Game
 *
 * @ORM\Table(name="game" , uniqueConstraints={@UniqueConstraint(name="unique", columns={"bgg_id", "name"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
{
    /****************************************
     *
     *          Relations
     *
     ***************************************/

    /**
     * @ORM\OneToMany(targetEntity="PlayLog", mappedBy="game")
     */
    private $playlogs;

    /**
     * @ORM\OneToMany(targetEntity="Expansion", mappedBy="game")
     */
    private $expansions;

    /**
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $users;

    /****************************************
     *
     *          Properties
     *
     ***************************************/


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
     * @ORM\Column(name="bgg_id", type="integer", length=255)
     */
    private $bgg_id;


    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /****************************************
     *
     *          Methods
     *
     ***************************************/

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }


    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->playlogs = new ArrayCollection();
        $this->expansions = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * Get bgg_id
     *
     * @return int
     */
    public function getBggId()
    {
        return $this->bgg_id;
    }
    /**
     * Set bgg_id
     *
     */
    public function setBggId($bgg_id)
    {
        $this->bgg_id = $bgg_id;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Game
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

//array collection functions

    /**
     * @return mixed
     */
    public function getPlaylogs()
    {
        return $this->playlogs;
    }


    /**
     * @return mixed
     */
    public function getExpansions()
    {
        return $this->expansions;
    }

    /**
     * @param mixed $expansions
     */
    public function setExpansions($expansions)
    {
        $this->expansions = $expansions;
    }

    public function addExpansion(Expansion $expansion)
    {
        $this->expansions->add($expansion);
        return $this;
    }

    public function removeExpansion(Expansion $expansion)
    {
        $this->expansions->removeElement($expansion);
    }


    public function removeAllExpansions()
    {
        foreach ($this->expansions as $expansion) {
            $this->removeExpansion($expansion);
        }
    }
}
