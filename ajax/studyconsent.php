<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 3/8/16
 * Time: 1:00 PM
 */
require_once '../dbcontroller.php';
require_once '../config/config.php';
require_once '../database/StudyConsent.php';
date_default_timezone_set('America/Chicago');

$objDBController = new DBController();
$dbconn=$objDBController->getConn();
$consent = new StudyConsent();

if(!empty($_POST['email'])) {
    $consent->setEmail($_POST['email']);
    $consent->setConsent(1);
    $consent->setConsentTime(date("Y-m-d H:i:s"));
    $consent->setUsername($_SESSION['email']);

    if($consent->saveConsent($dbconn)){
        echo "ok";
    }
}