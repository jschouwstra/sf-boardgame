<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlayLog
 *
 * @ORM\Table(name="play_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayLogRepository")
 */
class PlayLog
{
    /**************************
     *  Relations
     *************************/
    /**

     /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="playlogs")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="playlogs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**************************
     *  Getters and setters
     *************************/

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

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
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var int
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $user_id;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

//    /**
//     * @var int
//     *
//     * @ORM\Column(name="game_id", type="integer")
//     */
//    private $gameId;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return PlayLog
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

//    /**
//     * Set gameId
//     *
//     * @param integer $gameId
//     *
//     * @return PlayLog
//     */
//    public function setGameId($gameId)
//    {
//        $this->gameId = $gameId;
//
//        return $this;
//    }
//
//    /**
//     * Get gameId
//     *
//     * @return int
//     */
//    public function getGameId()
//    {
//        return $this->gameId;
//    }


//    public function setGame(Game $game)
//    {
//        $this->game = $game;
//    }


//    public function removeGame(Game $game)
//    {
//        $this->games->removeElement($game);
//    }


}

