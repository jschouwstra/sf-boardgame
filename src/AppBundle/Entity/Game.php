<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
{
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
     * Game constructor.
     */
    public function __construct()
    {
        $this->playlogs = new ArrayCollection();
        $this->expansions = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = "3",
     *  max = "100"
     * )
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

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




    public function addGameUser(User $user)
    {
        $this->users[] = $user;
    }
}
