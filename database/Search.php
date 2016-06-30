<?php

class Search
{
    private $searchTerm;
    private $date;
    private $uemail;
    private $searchType;
    private $searchId;



    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    public function setSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getUemail()
    {
        return $this->uemail;
    }

    public function setUemail($uemail)
    {
        $this->uemail = $uemail;
    }

    public function getSearchType()
    {
        return $this->searchType;
    }

    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;
    }

    public function getSearchId()
    {
        return $this->searchId;
    }

    public function setSearchId($searchId)
    {
        $this->searchId = $searchId;
    }


    public function saveSearch($dbconn){
        try{
            $stmt=$dbconn->prepare("INSERT INTO saved_search(create_time,search_term,user_email,search_type) VALUES (:time,:term,:uemail,:type)");
            $stmt->bindparam(":time",$this->date);
            $stmt->bindparam(":term",$this->searchTerm);
            $stmt->bindparam(":uemail",$this->uemail);
            $stmt->bindparam(":type",$this->searchType);
            $stmt->execute();

            $dbconn = null;
            return $stmt;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function showPartialSearch($dbconn){
        try{
            $stmt = $dbconn -> prepare("SELECT * FROM saved_search WHERE user_email=:user_email ORDER BY create_time DESC LIMIT 5");
            $stmt->bindparam(":user_email",$this->uemail);
            $stmt->execute();
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function showSearch($dbconn){
        try{
            $stmt = $dbconn->prepare("SELECT * FROM saved_search WHERE user_email=:user_email ORDER BY create_time DESC");
            $stmt->bindparam(":user_email",$this->uemail);
            $stmt->execute();
            $result = $stmt ->fetchAll();

            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function deleteSearch($dbconn){
        try{
            $stmt = $dbconn->prepare("DELETE FROM saved_search WHERE search_id= :search_id");
            $stmt->bindparam(":search_id",$this->searchId);
            $stmt->execute();

            return true;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }


    public function Is_valid_User($dbconn, $search_ID, $user_email){
        try{
            //Get the user email of the collection id
            $stmt = $dbconn -> prepare("SELECT user_email FROM biocaddie.saved_search WHERE search_id = :search_id");
            $stmt->bindparam(":search_id",$search_ID);
            $stmt->execute();
            $result = $stmt ->fetchAll();
            $email = $result[0][0];

            return $user_email == $email;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function If_User_Exist($dbconn,$user_email){
        try{
            $stmt = $dbconn -> prepare("SELECT * FROM biocaddie.user WHERE email = :email");
            $stmt->bindparam(":email",$user_email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }
}