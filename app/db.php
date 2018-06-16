<?php
namespace Test;

use \PDO;

class db{
    // Properties
    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dbname = 'unikee';

    //Connect
    public function connect(){
        try{
            $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";

            $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);

            $dbConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $dbConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

            return $dbConnection;
        }catch(PDOException $e){
            echo 'Connexion impossible. Erreur : ' . $e;
        }
    }
}
