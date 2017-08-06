<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Expansion
 *
 * @ORM\Table(name="expansion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExpansionRepository")
 */
class Expansion
{
    /**************************
     *  Relations
     *************************/

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="expansions")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**************************
     *  Properties
     *************************/


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

    /**************************
     *  Getters and setters
     *************************/
    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
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
     * @return Expansion
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

    //Array collection functions
    //  Every function about relations

    public function getExpansions()
    {
        return $this->expansions;
    }

    /**
     * @param mixed $games
     */
    public function setGames($games)
    {
        $this->games = $games;
    }

    public function addGame(Game $game)
    {
        $this->games->add($game);
        //     $this->games[] = $game;
        return $this;
    }



    public function removeExpansion(Expansion $expansion){
        $this->expansions->removeElement($expansion);
    }

    public function removeAllExpansions()
    {
        foreach ($this->expansion as $expansion)
        {
            $this->removeExpansion($expansion);
        }
    }
}

