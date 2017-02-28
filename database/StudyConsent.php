<?php

/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 3/8/16
 * Time: 1:18 PM
 */
class StudyConsent
{
    private $email;
    private $consent;
    private $consent_time;
    private $username;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getConsent()
    {
        return $this->consent;
    }

    /**
     * @param mixed $consent
     */
    public function setConsent($consent)
    {
        $this->consent = $consent;
    }

    /**
     * @return mixed
     */
    public function getConsentTime()
    {
        return $this->consent_time;
    }

    /**
     * @param mixed $consent_time
     */
    public function setConsentTime($consent_time)
    {
        $this->consent_time = $consent_time;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function saveConsent($dbconn){
        try{
            $stmt=$dbconn->prepare("INSERT INTO study_consent(email,consent,consent_time,username) VALUES (:email,:consent,:consent_time,:username)");
            $stmt->bindparam(":email",$this->email);
            $stmt->bindparam(":consent",$this->consent);
            $stmt->bindparam(":consent_time",$this->consent_time);
            $stmt->bindparam(":username",$this->username);
            $stmt->execute();

            $dbconn = null;
            return $stmt;

        }catch(PDOException $e){
            echo "You have already agreed to participate in thie study.";
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
            echo "You have already agreed to participate in thie study.";
        }

    }

}