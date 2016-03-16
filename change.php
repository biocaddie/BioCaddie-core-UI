<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('America/chicago');
require_once dirname(__FILE__) . '/lib/password.php';

require_once './dbcontroller.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();


// Was the form submitted?
if (isset($_POST["ForgotPassword"])) {

    // Harvest submitted e-mail address
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email = $_POST["email"];

    }else{
        echo "email is not valid";
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
        $pwrurl = "http://datamed.biocaddie.org/dev/biocaddie-ui/reset_password.php?q=".$password;

        $from = 'biocaddie.mail@gmail.com';
        $to = $userExists["email"];
        $subject = " Password Reset";
        $body = 'Dear user,<br>
                If this e-mail does not apply to you please ignore it. It appears that you have requested a password reset at our website www.yoursitehere.com<br>
                To reset your password, please click the link below. If you cannot click it, please paste it into your web browser\'s address bar.<br>'
                . $pwrurl . '<br>Thanks,<br>The Administration';


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

	require_once './views/header.php';

        echo "<div class='container'>Your password recovery key has been sent to your e-mail address.</div>";
      //  header( "refresh:5;url=../index.php" );
        include dirname(__FILE__) . '/views/footer.php';

    }
    else
        echo "No user with that e-mail address exists.";
}
?>
