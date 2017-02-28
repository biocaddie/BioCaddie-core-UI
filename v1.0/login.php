<?php
require_once dirname(__FILE__) .'/config/config.php';
require_once './lib/password.php';
require_once 'dbcontroller.php';
require_once './database/User.php';
require_once './database/GoogleUser.php';
require_once './database/Search.php';
require_once './database/UserCollection.php';
require_once dirname(__FILE__) .'/search/SubmitDataService.php';

require_once dirname(__FILE__) . '/trackactivity.php';

if(!isset($_SESSION['loggedin'])){
    $_SESSION['loggedin']=false;
}

$uemail=null;

/******** bioCADDIE Login ********/
if(isset($_POST['btn-login'])){
    $uemail=$_POST['txt_uemail'];
    $upass=$_POST['txt_password'];

    $user=new User();
    $objDBController = new DBController();
    $dbconn=$objDBController->getConn();

    if($user->login($dbconn,$uemail,$upass)){
        $_SESSION['loggedin']=true;
        $_SESSION['name']=$user->getUname();
        $_SESSION['email']=$uemail;
        $_SESSION['user_id']=$user->getUid();

        $user->redirect('login.php');
    }else{
        $error[]= "The email and password you entered don't match.";
    }
}

/******** Google Login ********/
include dirname(__FILE__) . '/google_login.php';

//Logout
if (isset($_REQUEST['logout'])) {
    session_destroy();
    $client->revokeToken();
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect database back to page
}

// if isset($authUrl)==false or $loggedIn = true show profile page (viewprofile.php)
if(!isset($authUrl) || $_SESSION['loggedin']){
    if(isset($userData)){
        $_SESSION['name']=$userData["name"];
        $_SESSION['email']=$userData["email"];
    }else{

        $userData['picture']="./img/user.png";
        $userData['link']="";
        $userData['name']=$_SESSION['name'];
        $userData['email']=$uemail;
    }
    include dirname(__FILE__) . '/views/header.php';
    include dirname(__FILE__) . '/views/account/viewprofile.php';
}// if isset($authUrl)==true and $loggedIn = false show login page (viewlogin.php)
elseif(isset($authUrl) &&!$_SESSION['loggedin']){
    include dirname(__FILE__) . '/views/header.php';
    include dirname(__FILE__) . '/views/account/viewlogin.php';
}
?>


<?php
/* Page Custom Scripts. */
$scripts = [
    "./js/page.scripts/login.js"];
?>
<?php include dirname(__FILE__) . '/views/footer.php'; ?>
