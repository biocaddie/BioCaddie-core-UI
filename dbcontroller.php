<?php
class DBController {
    private $host = "129.106.31.121";
    //private $host = "localhost"; //"129.106.31.121"
    private $user = "biocaddie";
    private $password = "biocaddie";
    private $database = "biocaddie";

    private $conn =null;

    public function getConn()
    {
        return $this->conn;
    }

    function __construct() {
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
