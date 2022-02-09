<?php


class Authentication
{
    private $db;

    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PW,);
        } catch (PDOException $e){
            echo "Connection Failed";
            die();
        }
    }

    public function getUser()
    {
        $statement = $this->db->prepare("SELECT * FROM Users");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function login($user, $password)
    {
        foreach ($this->getUser() as $a) {
            if ($a['Username'] == $user && $a['Password'] == $password) {
                return true;
            }
        }
        return false;
    }


    public function registerCheck($user, $email){
        foreach ($this->getUser()  as $a) {
            if ($a['Username'] == $user || $a['Email'] == $email) {
                return false;
            }
        }
        return true;
    }



    public function register($user, $password, $email, $profilepic){
        $statement = $this->db->prepare("INSERT INTO Users (Username, Password, Email, Profilepic) VALUES (:username, :password, :email, :profilepic)");
        $statement->bindValue(":username", $user);
        $statement->bindValue(":password", $password);
        $statement->bindvalue(":profilepic",$profilepic);
        $statement->bindvalue(":email",$email);
        $statement->execute();
        print_r($statement->errorInfo());
    }
}