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
            $sql ="SELECT * FROM saved_search WHERE user_email="."'".$this->uemail."' ORDER BY create_time DESC LIMIT 5";
            $result = $dbconn->query($sql);

            $dbconn = null;
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function showSearch($dbconn){
        try{
            $sql ="SELECT * FROM saved_search WHERE user_email="."'".$this->uemail."' ORDER BY create_time DESC";
            $result = $dbconn->query($sql);

            $dbconn = null;
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function deleteSearch($dbconn){
        try{
            var_dump($this->searchId);
            $sql ="DELETE FROM saved_search WHERE search_id=".$this->searchId;
            $result = $dbconn->query($sql);
            $dbconn = null;
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}