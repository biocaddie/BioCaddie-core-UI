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


    public function createCollection ($dbconn)
    {
        $getUserEmail = $this->getUserEmail();
        $getCollectionName = $this->getCollectionName();
        $getCreateTime = $this->getCreateTime();
        $getSettings = $this->getSettings();

        try{
            $stmt=$dbconn->prepare("INSERT INTO user_collections(user_email,collection_name,create_time, settings) VALUES (:user_email,:collection_name,:create_time,:settings)");
            $stmt->bindparam(":user_email",$getUserEmail);
            $stmt->bindparam(":collection_name",$getCollectionName);
            $stmt->bindparam(":create_time",$getCreateTime);
            $stmt->bindparam(":settings",$getSettings);
            $stmt->execute();

            $result = $dbconn->lastInsertId();
            return $result;

        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function getCollections ($dbconn)
    {
        $getUserEmail = $this->getUserEmail();
        try{
            $stmt = $dbconn->prepare("SELECT collection_name FROM user_collections WHERE user_email=:uemail");
            $stmt -> execute(array(':uemail'=>$getUserEmail));
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function searchCollectionId($dbconn)
    {
        $getUserEmail = $this->getUserEmail();
        $getCollectionName = $this->getCollectionName();
        try{
            $stmt = $dbconn->prepare("SELECT collection_id FROM user_collections WHERE user_email=:user_email AND collection_name=:collection_name");
            $stmt->bindparam(":user_email",$getUserEmail);
            $stmt->bindparam(":collection_name",$getCollectionName);
            $stmt -> execute();
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function showPartialCollections($dbconn)
    {
        $getUserEmail = $this->getUserEmail();
        try{
            $stmt = $dbconn->prepare("SELECT * FROM user_collections WHERE user_email=:user_email ORDER BY create_time DESC LIMIT 5");
            $stmt->bindparam(":user_email",$getUserEmail);
            $stmt -> execute();
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function showCollections($dbconn)
    {
        $getUserEmail = $this->getUserEmail();
        try{
            $stmt = $dbconn->prepare("SELECT * FROM user_collections WHERE user_email=:user_email ORDER BY create_time DESC");
            $stmt->bindparam(":user_email",$getUserEmail);
            $stmt -> execute();
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

    public function is_collection_name_exist($dbconn,$newname)
    {
        $getUserEmail = $this->getUserEmail();
        try{
            $stmt = $dbconn->prepare("SELECT collection_name FROM user_collections WHERE user_email=:uemail AND collection_name=:collection_name");
            $stmt->bindparam(":uemail",$getUserEmail);
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

    public function deleteCollection($dbconn)
    {
        $getCollectionId = $this->getCollectionId();
        try{
            $stmt = $dbconn->prepare("DELETE FROM user_collections WHERE collection_id=:collection_id");
            $stmt->bindparam(":collection_id",$getCollectionId);
            $stmt -> execute();

            return true;

        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function Is_valid_User($dbconn, $collection_id, $user_email)
    {
        try{

            //Get the user email of the collection id
            $stmt = $dbconn -> prepare("SELECT user_email FROM biocaddie.user_collections WHERE collection_id = :collection_id");
            $stmt->bindparam(":collection_id",$collection_id);
            $stmt->execute();
            $result = $stmt ->fetchAll();
            $email = $result[0][0];

            return $user_email == $email;

        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }

}