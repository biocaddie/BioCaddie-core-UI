<?php
require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__).'/lib/password.php';
require_once dirname(__FILE__).'/Model/DBController.php';
require_once dirname(__FILE__).'/database/User.php';
require_once dirname(__FILE__).'/database/GoogleUser.php';
require_once dirname(__FILE__).'/database/Search.php';
require_once dirname(__FILE__).'/database/UserCollection.php';
require_once dirname(__FILE__) .'/Model/SubmitDataService.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

$pageTitle = "Sign in - DataMed Accounts";

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
    $redirect_uri = (( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://' ) .$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI'])."/login.php";
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL)); //redirect database back to page
}


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
}
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
