<?php

namespace AppBundle\Entity;
namespace AppBundle\Entity\Game;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 *
 *
**/


class Category
{

/**
 * @ManyToMany(targetEntity="Game")
 * @JoinTable(name="category_game",
 *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
 *      inverseJoinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")}
 *      )
 */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
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
     * @ORM\Column(name="name", type="string", length=255)
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
     * @return Category
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

}
