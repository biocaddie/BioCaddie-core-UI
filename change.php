<?php

$pageTitle = "Change Password";

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('America/chicago');
require_once dirname(__FILE__) . '/lib/password.php';

require_once dirname(__FILE__).'/Model/DBController.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();


// Was the form submitted?
if (isset($_POST["ForgotPassword"])) {

    // Harvest submitted e-mail address
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST["email"];

    }else{
        include dirname(__FILE__) . '/views/header.php';
        echo "email is not valid";
        include dirname(__FILE__) . '/views/footer.php';
        exit;
    }

    // Check to see if a user exists with this e-mail
    $query = $dbconn->prepare('SELECT email FROM user WHERE email = :email');
    $query->bindParam(':email', $email);
    $query->execute();
    $userExists = $query->fetch(PDO::FETCH_ASSOC);
    $dbconn = null;

    if ($userExists["email"])
    {
        require_once dirname(__FILE__) . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

        // Create a unique salt. This will never leave PHP unencrypted.
        $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

        // Create the unique user password reset key
        $password = hash('sha512', $salt.$userExists["email"]);

        // Create a url which we will direct them to reset their password
        $pwrurl = (( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://' ) .$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI'])."/reset_password.php?q=".$password;

        $from = 'biocaddie.mail@gmail.com';
        $to = $userExists["email"];
        $subject = " Password Reset";
        $body = 'Dear user,<br><br>
                If this e-mail does not apply to you please ignore it. It appears that you have requested a password reset at our website https://datamed.org<br>
                To reset your password, please click the link below. If you cannot click it, please paste it into your web browser\'s address bar.<br><br>'
                . $pwrurl . '<br><br>Thanks,<br>The DataMed Team';


        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
            ->setUsername('biocaddie.mail@gmail.com')
            ->setPassword('biocaddie4050@');

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance('DataMed -' . $subject)
            ->setFrom(array($from => 'bioCaddie'))
            ->setTo(array($to))
            ->setBody($body)
            ->setContentType("text/html");

        $result = $mailer->send($message);

        include dirname(__FILE__) . '/views/header.php';

        echo "<div class='container'>Your password recovery key has been sent to your e-mail address.</div>";
      //  header( "refresh:5;url=../index.php" );
        include dirname(__FILE__) . '/views/footer.php';

    }
    else{
        include dirname(__FILE__) . '/views/header.php';
        echo "<div class='container'>No user with that e-mail address exists.</div>";
        include dirname(__FILE__) . '/views/footer.php';
    }

}else{
    header('Location: index.php');
    exit;
}
?>
