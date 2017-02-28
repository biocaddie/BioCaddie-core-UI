<?php

class GoogleUser
{

    private $dbconn;


    public function getDbconn()
    {
        return $this->dbconn;
    }

    public function setDbconn($dbconn)
    {
        $this->dbconn = $dbconn;
    }

    function getUserByOAuthId($oauth_user_id) {

        try {
            $query = $this->dbconn->prepare("SELECT * FROM google_user WHERE oauth_user_id=?");
            $query->bindValue(1, $oauth_user_id, PDO::PARAM_STR);
            $query->execute();
        }catch(PDOException $e){
            echo $e->getMessage();
        }

        $row_count=$query->rowCount();

        if($row_count>0) {
            $existing_member = $query->fetch(PDO::FETCH_ASSOC);
            return $existing_member;
        }
    }

    function insertOAuthUser($userData) {
        $currenttime=date("Y-m-d H:i:s");

        /* insert user into user table */
        try {
            $user_query = $this->dbconn->prepare("INSERT INTO user(username,email,create_time) VALUES (:username,:email,:create_time)");
            $user_query->execute(array(':username' => $userData->name, ':email' => $userData->email, ':create_time' => $currenttime));
        }catch(PDOException $e){
            echo $e->getMessage();
        }

        /*get user from user table*/
        try{
            $id_query=$this->dbconn->prepare("SELECT user_id FROM user WHERE email=?");
            $id_query->bindValue(1,$userData->email,PDO::PARAM_STR);
            $id_query->execute();

            $user_id=$id_query->fetchColumn();

        }catch(PDOException $e){
            echo $e->getMessage();
        }

        /* insert database into google_user table */
        try{
            $query=$this->dbconn->prepare("INSERT INTO google_user(member_name, member_email, oauth_user_id, oauth_user_page, oauth_user_photo,user_id)
                                      VALUES (:member_name, :member_email, :oauth_user_id, :oauth_user_page, :oauth_user_photo,:user_id)");
            $query->execute(array(':member_name'=>$userData->name, ':member_email'=>$userData->email, ':oauth_user_id'=>$userData->id,
                ':oauth_user_page'=> $userData->link, ':oauth_user_photo'=>$userData->picture,':user_id'=>$user_id));
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

}