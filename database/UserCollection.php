<?php
/**
 * Created by PhpStorm.
 * User: ruilingliu
 * Date: 5/1/16
 * Time: 7:30 PM
 */

class UserCollection{

    private $user_email;
    private $collection_name;
    private $create_time;
    private $settings;
    private $collection_id;

    public function getCollectionId()
    {
        return $this->collection_id;
    }

    public function setCollectionId($collection_id)
    {
        $this->collection_id = $collection_id;
    }


    public function getCreateTime()
    {
        return $this->create_time;
    }

    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;
    }



    public function getUserEmail()
    {
        return $this->user_email;
    }

    public function setUserEmail($user_email)
    {
        $this->user_email = $user_email;
    }


    public function getCollectionName()
    {
        return $this->collection_name;
    }

    public function setCollectionName($collection_name)
    {
        $this->collection_name = $collection_name;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }


    public function createCollection ($dbconn){
        try{
            $stmt=$dbconn->prepare("INSERT INTO user_collections(user_email,collection_name,create_time, settings) VALUES (:user_email,:collection_name,:create_time,:settings)");
            $stmt->bindparam(":user_email",$this->getUserEmail());
            $stmt->bindparam(":collection_name",$this->getCollectionName());
            $stmt->bindparam(":create_time",$this->getCreateTime());
            $stmt->bindparam(":settings",$this->getSettings());
            $stmt->execute();

            $result = $dbconn->lastInsertId();

            $dbconn = null;
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function getCollections ($dbconn){
        try{
            $stmt = $dbconn->prepare("SELECT collection_name FROM user_collections WHERE user_email=:uemail");
            $stmt -> execute(array(':uemail'=>$this->user_email));
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function searchCollectionId($dbconn){
        try{
            $stmt = $dbconn->prepare("SELECT collection_id FROM user_collections WHERE user_email=:user_email AND collection_name=:collection_name");
            $stmt->bindparam(":user_email",$this->user_email);
            $stmt->bindparam(":collection_name",$this->collection_name);
            $stmt -> execute();
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function showPartialCollections($dbconn){
        try{

            $sql ="SELECT * FROM user_collections WHERE user_email="."'".$this->user_email."' ORDER BY create_time DESC LIMIT 5";
            $result = $dbconn->query($sql);

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function showCollections($dbconn){
        try{

            $sql ="SELECT * FROM user_collections WHERE user_email="."'".$this->user_email."' ORDER BY create_time DESC";
            $result = $dbconn->query($sql);

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function is_collection_name_exist($dbconn,$newname){
        try{
            $stmt = $dbconn->prepare("SELECT collection_name FROM user_collections WHERE user_email=:uemail AND collection_name=:collection_name");
            $stmt->bindparam(":uemail",$this->getUserEmail());
            $stmt->bindparam(":collection_name",$newname);
            $stmt -> execute();

            $rowCount = $stmt ->rowCount();

            if($rowCount>0){
                $error[]="Please provide a unique collection name. A collection with the same name already exists.";
                return true;
            }else{
                return false;
            }

        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function deleteCollection($dbconn){
        try{
            $sql ="DELETE FROM user_collections WHERE collection_id=".$this->getCollectionId();
            $result = $dbconn->query($sql);
            $dbconn = null;
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }



}