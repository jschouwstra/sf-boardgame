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
    public function __construct()
    {
        $this->playlogs = new ArrayCollection();
    }

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="games")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;


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

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @ORM\Column (options={"default" = none})
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return mixed
     */
    public function getPlaylogs()
    {
        return $this->playlogs;
    }

    /**
     * @param mixed $playlogs
     */
    public function setPlaylogs($playlogs)
    {
        $this->playlogs = $playlogs;
    }

    public function addPlayLog(PlayLog $playlog)
    {
        $this->playlog->add($playlog);
        $playlog->setPlayLogs($this);
    }

}
