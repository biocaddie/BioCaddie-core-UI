<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/21/16
 * Time: 2:06 PM
 */

require_once dirname(__FILE__).'/../vendor/autoload.php';

function sendEmails(){
    $subject = $_POST['SUBJECT'];

    require_once dirname(__FILE__) . '/../vendor/swiftmailer/swiftmailer/lib/swift_required.php';

    $from = $_POST["EMAIL"];
    $to = array("xiaoling.chen@uth.tmc.edu","ruiling.liu@uth.tmc.edu","Anupama.E.Gururaj@uth.tmc.edu","Mandana.Salimi@uth.tmc.edu");

    $body = 'bioCADDIE contact request<br>
        ----------------------------------------<br>
        NAME: '.$_POST["NAME"].'<br>
        MESSAGE: '.$_POST["MESSAGE"].'<br>
        EMAIL: '.$_POST["EMAIL"];

    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
        ->setUsername('biocaddie.mail@gmail.com')
        ->setPassword('biocaddie4050@');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('bioCADDIE Contact us email:' . $subject)
        ->setFrom(array($from => 'bioCADDIE'))
        ->setTo($to)
        ->setBody($body)
        ->setContentType("text/html");
    $mailer->send($message);
}

function postToGitHub(){
    $client = new \Github\Client();
    $client->authenticate('biocaddie.mail@gmail.com', 'biocaddie4050@', Github\Client::AUTH_HTTP_PASSWORD);
    $client->api('issue')->create('biocaddie', 'prototype_issues', array('title' => $_POST['SUBJECT'], 'body' => $_POST["MESSAGE"]));
}

?>