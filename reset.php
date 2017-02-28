<?php

require_once dirname(__FILE__) . '/lib/password.php';
require_once dirname(__FILE__) .'/Model/DBController.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();


// Was the form submitted?
if (isset($_POST["ResetPasswordForm"]))
{
    // Gather the post data

    $email = str_replace(' ', '',$_POST["email"]);

    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $hash = $_POST["q"];

    // Use the same salt from the forgot_password.php file
    $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

    // Generate the reset key
    $resetkey = hash('sha512', $salt.$email);

    // Does the new reset key match the old one?
    if ($resetkey == $hash)
    {
        if ($password == $confirmpassword)
        {
            //has and secure the password
            $password = password_hash($password,PASSWORD_DEFAULT);

            // Update the user's password
            $query = $dbconn->prepare('UPDATE user SET password = :password WHERE email = :email');
            $query->bindParam(':password', $password);
            $query->bindParam(':email', $email);
            $query->execute();
            $dbconn = null;

            include dirname(__FILE__) . '/views/header.php';
            echo "<div class='container'>Your password has been successfully reset.</div>";
            include dirname(__FILE__) . '/views/footer.php';
        }
        else{
            include dirname(__FILE__) . '/views/header.php';
            echo "<div class='container'>Your password's do not match.</div>";
            include dirname(__FILE__) . '/views/footer.php';
        }
    }
    else{
        include dirname(__FILE__) . '/views/header.php';
        echo "<div class='container'>Your password reset key is invalid.</div>";
        include dirname(__FILE__) . '/views/footer.php';
    }

}else{
    header('Location: index.php');
    exit;
}

?>
