<?php


class Follow
{
    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PW,);
        } catch (PDOException $e){
            echo "Connection Failed";
            die();
        }
    }

    function follow($userID, $follow, $state){
        $statement = $this->db->prepare("INSERT INTO Following (UserID, Follows, Status) VALUES (:userID, :follow, :status)");
        $statement->bindValue(":userID", $userID);
        $statement->bindValue(":follow",$follow);
        $statement->bindValue(":status", $state);
        $statement->execute();
        print_r($statement->errorInfo());
    }

    function addUser($userID){
        $statement = $this->db->prepare("INSERT INTO Following (UserID) VALUES (:userID)");
        $statement->bindValue(":userID", $userID);
        $statement->execute();
        print_r($statement->errorInfo());
    }

    function updateState($userID, $following, $state){
        $statement = $this->db->prepare("UPDATE Following SET Status = :status WHERE Follows = :follows AND UserID = :userID");
        $statement->bindValue(":userID", $userID);
        $statement->bindValue(":follows", $following);
        $statement->bindValue(":status", $state);
        $statement->execute();
    }

    function updateFollow($userID, $following){
        $statement = $this->db->prepare("UPDATE Following SET Status = 1, Follows = :follows WHERE UserID = :userID");
        $statement->bindValue(":userID", $userID);
        $statement->bindValue(":follows", $following);
        $statement->execute();
    }


    public function getFollow()
    {
        $statement = $this->db->prepare("SELECT * FROM Following");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


}