<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 3/17/16
 * Time: 2:56 PM
 */
require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__).'/Model/DBController.php';

// Check database

$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$today= date("Y-m-d");
$year = date("Y", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
$month = date("m", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
$date = date("d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );

$stmt = $dbconn->prepare("SELECT * FROM study_consent WHERE YEAR(consent_time)=:year AND MONTH(consent_time)=:month AND DAYOFMONTH(consent_time)=:date");//" WHERE YEAR(consent_time)=:year AND MONTH(consent_time)=:month AND DATE(consent_time)=:date");
$stmt->execute(array(':year'=>$year,':month'=>$month,':date'=>$date));

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $email = $row['email'];
    sendEmails($email);

}

function sendEmails($email){
    $subject = 'Follow-up survey for DataMed';

    require_once dirname(__FILE__) . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

    $from = 'biocaddie.mail@gmail.com';
    $to = array($email);
    $body = 'Dear user,<br><br>
        We want to thank you for using the DataMed website and the willingness to participate in the User Testing.<br><br>
        Please complete our survey so we can improve it in the future. We believe your voice will be a critical addition to the development of the bioCADDIE prototype.<br><br>
        Here is a link to the survey: https://docs.google.com/forms/d/15Rx5wvmmeH1fUEe2JensTeaiElE1cnO7vtAt9MQ8FJg/viewform<br><br>
        Thank you and have a great day!<br><br>
        Best,<br>
        The bioCADDIE team';

    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
        ->setUsername('biocaddie.mail@gmail.com')
        ->setPassword('biocaddie4050@');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('bioCADDIE :' . $subject)
        ->setFrom(array($from => 'bioCADDIE'))
        ->setTo($to)
        ->setBody($body)
        ->setContentType("text/html");
    $mailer->send($message);
}