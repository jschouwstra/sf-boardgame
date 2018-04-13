<?php
/**
 * Created by PhpStorm.
 * User: Jelle
 * Date: 11-4-2018
 * Time: 16:38
 */

namespace AppBundle\Entity;


use function mysqli_query;

class DatabaseWrapper
{
    public function DBConnection()
    {
        $con = mysqli_connect("localhost", "root", "", "test");
        if (mysqli_connect_errno()) {
            return "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {
            return $con;
        }

    }

    public function insertGames()
    {

        $sql = "insert into game (
                    'name','bgg_id'
                  ) VALUES(
                    'gameName','bggID'
                  )";

        if(mysqli_query($this->DBConnection(), $sql)){
            "new record added";
        }else{
            mysqli_error($this->DBConnection());

        }
    }
}