<?php

class User
{
     private $uname;
     private $uemail;
     private $upass;
     private $uid;

    public function getUid()
    {
        return $this->uid;
    }

    public function getUname()
    {
        return $this->uname;
    }

    public function setUname($uname)
    {
        $this->uname = $uname;
    }

    public function getUemail()
    {
        return $this->uemail;
    }

    public function setUemail($uemail)
    {
        $this->uemail = $uemail;
    }

    public function getUpass()
    {
        return $this->upass;
    }

    public function setUpass($upass)
    {
        $this->upass = $upass;
    }


    public function register($dbconn, $uname, $uemail, $upass,$currenttime){
        try{
            $stmt=$dbconn->prepare("INSERT INTO user(username,email,password,create_time) VALUES (:uname,:uemail,:upass,:create_time)");
            $stmt->bindparam(":uname",$uname);
            $stmt->bindparam(":uemail",$uemail);
            $stmt->bindparam(":upass",$upass);
            $stmt->bindparam(":create_time",$currenttime);
            $stmt->execute();

            return $stmt;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function login($dbconn,$uemail,$upass){
        $this->uemail=$uemail;
        $this->upass=$upass;

        try{
            $stmt=$dbconn->prepare("SELECT * FROM user WHERE email=:uemail LIMIT 1");
            $stmt->execute(array(':uemail'=>$uemail));

            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount()>0){
                if(password_verify($upass,$userRow['password'])){
                    $_SESSION['user_session']=$userRow['user_id'];
                    $_SESSION['name']=$userRow['username'];
                    $this->uname=$userRow['username'];
                    $this->uid=$userRow['user_id'];
                    return true;
                }else{
                    return false;
                }
            }

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function is_user_exist($dbconn, $uemail){
        try{
            $stmt = $dbconn->prepare("SELECT email FROM user WHERE email=:uemail");

            $stmt -> execute(array(':uemail'=>$uemail));
            $rowCount = $stmt ->rowCount();
             echo $rowCount;

            if($rowCount>0){
                $error[]="Someone already register using that email. Try another?";
                return true;
            }else{
                echo "new database";
                return false;
            }

        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function is_loggedin(){
        if(isset($_SESSION['username'])){
            return true;
        }
    }

    public function redirect($url){
        header("Location:$url");
    }

    public function logout(){
        session_destroy();
        unset($_SESSION['user_session']);
        unset($_SESSION['name']);
        $_SESSION['loggedin']=false;
        return true;
    }
}

?>
