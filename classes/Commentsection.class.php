<?php


class Commentsection
{
    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PW,);
        } catch (PDOException $e){
            echo "Connection Failed";
            die();
        }
    }

    public function addComment($userID, $imageID, $text){
        $statement = $this->db->prepare("INSERT INTO Comments (UserID, ImageID, text) VALUES (:userID, :imageID, :text)");
        $statement->bindValue(":userID", $userID);
        $statement->bindValue(":imageID", $imageID);
        $statement->bindValue(":text", $text);
        $statement->execute();
        print_r($statement->errorInfo());
    }

    public function getComments(){
        $statement = $this->db->prepare("SELECT * FROM Comments ORDER BY ID DESC");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}