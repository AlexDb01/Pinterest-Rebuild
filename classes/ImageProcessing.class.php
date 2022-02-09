<?php


class ImageProcessing
{
    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PW,);
        } catch (PDOException $e){
            echo "Connection Failed";
            die();
        }
    }




    public function getImg()
    {
        $statement = $this->db->prepare("SELECT * FROM Images ORDER BY ID DESC");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function imgDbUpload($userID, $image, $name){
        $statement = $this->db->prepare("INSERT INTO Images (UserID, image, name) VALUES (:UserID, :image, :name)");
        $statement->bindValue(":UserID", $userID);
        $statement->bindValue(":image", $image);
        $statement->bindValue(":name", $name);
        $statement->execute();
        print_r($statement->errorInfo());
    }

    public function changePfP($imageName, $userID){
        $statement = $this->db->prepare("UPDATE Users SET Profilepic = :imageName WHERE ID = :userID");
        $statement->bindValue(":userID", $userID);
        $statement->bindValue(":imageName", $imageName);
        $statement->execute();
        print_r($statement->errorInfo());
    }

}