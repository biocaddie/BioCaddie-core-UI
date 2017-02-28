
<?php

$pageTitle = "Create your DataMed Account";

require_once dirname(__FILE__) .'/config/config.php';
require_once './lib/password.php';
date_default_timezone_set('America/Chicago');
require_once dirname(__FILE__).'/Model/DBController.php';
require_once dirname(__FILE__).'/database/User.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

if(isset($_POST['btn-signup'])){
    $uname=trim($_POST['txt_uname']);
    $uemail=trim($_POST['txt_uemail']);
    $upass=trim($_POST['txt_upass']);
    $ucpass=trim($_POST['txt_ucpass']);

    $_SESSION['email']=$uemail;
    $_SESSION['uname']=$uname;

    if($uname==""){
        $error[]="Please enter screen name!";
    }
    elseif($uemail==""){
        $error[]="Please enter email!";
    }
    else if(!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Please enter a valid email address !';
    }
    elseif($upass==""){
        $error[]="Please enter password!";
    }
    elseif($ucpass==""){
        $error[]="Please enter password again!";
    }else{
        $objDBController = new DBController();
        $dbconn=$objDBController->getConn();
        $currenttime=date("Y-m-d H:i:s");

        $user = new User();
        if(!$user->is_user_exist($dbconn,$uemail)){
            echo "new database";
            if($user->register($dbconn,$uname,$uemail,password_hash($upass,PASSWORD_DEFAULT),$currenttime)){
                $user->redirect('register.php?joined');
            }
        }else{
            $error[]="The email address you have entered is already registered";
        }
    }

}

include dirname(__FILE__) . '/views/header.php';
include dirname(__FILE__) . '/views/account/viewregister.php';
?>

<?php
/* Page Custom Scripts. */
$scripts = [
    "./js/page.scripts/register.js"];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>
