<?php

require_once  dirname(__FILE__).'/../config/config.php';

class DBController {

    private $host;
    private $password;
    private $database;

    private $conn =null;

    public function getConn()
    {
        return $this->conn;
    }

    function __construct() {
        global $dbconf;
        $this->host = $dbconf['ip'];
        $this->user = $dbconf['user'];
        $this->password = $dbconf['password'];
        $this->database = $dbconf['database'];

        $this->conn = $this->connectDB();
        if(!empty($conn)) {
            $this->selectDB($conn);
        }
    }

    function __destruct() {
       $this->conn=null;
    }

    function connectDB() {
        try{
            $db=new PDO('mysql:host='.$this->host.';dbname='.$this->database.';charset=utf8',$this->user,$this->password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        return $db;
    }
}
?>
