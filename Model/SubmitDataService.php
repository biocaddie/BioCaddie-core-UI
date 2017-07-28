<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 5/10/16
 * Time: 10:48 AM
 */
require_once  dirname(__FILE__).'/../config/config.php';
require_once dirname(__FILE__).'/DBController.php';
require_once  dirname(__FILE__).'/../vendor/swiftmailer/swiftmailer/lib/swift_required.php';
require_once dirname(__FILE__).'/../vendor/autoload.php';

/*
 * get showing field name for submit repo page
 * @return array(string)
 */
function get_repository_showing_label()
{
    $showing_label = [
        "submitter_name" => "Submitter's Name",
        "submitter_email" => "Submitter Email",
        "submitter_organization" => "Submitter's Organization",

        'contact_person'=> "Contact Person",
        'contact_email'=> "Contact Email",

        "datarepo_name" => "Repository Full Name",
        "datarepo_abbr" => "Repository Abbreviation",
        "datarepo_homepage" => "Repository Homepage URL",
        "datarepo_datatype"=> "Repostitory Type of content",

        "datarepo_size" => "How much data (bytes, number of objects) are available to the scientific community?",
        'use_agreement'=> "Is a data use agreement required?",
        'created_time'=> "When was the repository created?",
        'repo_funded'=> "How is the repository funded?",
        "in_biosharing"=>'BioSharing.org ID for repository',
        'data_identifiers'=>'What dataset identifiers are used?',
        'metadata_standards'=>'What metadata standards are used?',
        'metadata_in_format'=>'Is metadata accessible in a machine-actionable format (XML, RDM, etc.)?'
    ];
    return $showing_label;
}
/*
 * get id for submit repo page
 * @return array(string)
 */
function get_repository_ids(){
    $ids = [
        "submitter_name",
        "submitter_email",
        "submitter_organization",

        'contact_person',
        'contact_email',

        "datarepo_name",
        "datarepo_abbr",
        "datarepo_homepage",
        "datarepo_datatype",


        "datarepo_size",
        "use_agreement",
        'created_time',
        'repo_funded',
        "in_biosharing",
        'data_identifiers',
        'metadata_standards',
        'metadata_in_format'
    ];
    return $ids;
}

/*
 * show submitted repo
 * @return array(string)
 */
function show_submitted_repository($objDBController,$selectItems){
    $query_map = [
        "all"=>"Select * from submitted_repository_new",
        "reviewed"=>"Select * from submitted_repository_new WHERE reviewed = 'Yes'",
        "notreviewed"=>"Select * from submitted_repository_new  WHERE (reviewed ='' or reviewed is NULl)"
    ];
    $query = $query_map[$selectItems];
    $dbconn=$objDBController->getConn();
    $stmt = $dbconn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    $column = get_repository_ids();
    $result_arrays=[];
    foreach($results as $result){
        $result_array=[];
        foreach(array_keys($result) as $key){
            if($key=='reviewed'){
                $result_array[$key]=$result[$key];
            }
            elseif($key=='ID'){
                $result_array['ID']=$result['ID'];
            }

            if(in_array($key,$column)){
                if($key=='ID') {
                    continue;
                }
                elseif(strlen($result[$key])>0){
                    $result_array[$key]=$result[$key];
                }
            }
        }
        array_push($result_arrays,$result_array);

    }
    return $result_arrays;
}

/*
 * check manager id before access the page
 * @return boolean
 */
function check_manager_id($objDBController,$id){
    if(strlen($id)>0) {
        $dbconn = $objDBController->getConn();
        $stmt = $dbconn->prepare("Select * from manager_user WHERE user_id=:id");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        $results = $stmt->fetchAll();
        if (sizeof($results) > 0) {
            return true;
        }
    }
    return false;
}
/*
 * check manager email before access the page
 * @return boolean
 */
function check_manager_email($objDBController,$email){
    if(strlen($email)>0) {
        $dbconn = $objDBController->getConn();
        $stmt = $dbconn->prepare("Select * from manager_user WHERE email=:email");
        $stmt->bindparam(":email",$email);
        $stmt->execute();
        $results = $stmt->fetchAll();
        if (sizeof($results) > 0) {
            return true;
        }
    }
    return false;
}


/*
 * for manage_submit_repo.php page
 */
function change_review_to_db($dbconn,$data)
{

    foreach (array_keys($data) as $key) {
        if (substr($key, 0, 7) == 'review_') {
            $id = substr($key, 7);
            $status = $data[$key];
            if ($data[$key] == 'Yes') {
                try {
                    $stmt = $dbconn->prepare("UPDATE submitted_repository_new SET reviewed=? where ID=?");
                    $stmt->bindparam(1, $status);
                    $stmt->bindparam(2, $id);
                    $stmt->execute();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }
}

/*
 * submit repo info to database
 */


function submit_repository($dbconn, $result)
{
    array_push($result,['submit_time',date('Y-m-d')]);
    $column = '';
    $column_value = '';
    $data = [];
    foreach($result as $item){
        $id = $item[0];
        $data[$id]=$item[1];
        if(strlen($column)>0) {
            $column = $column . ',' . $id;
            $column_value = $column_value . ',' . ':'.$id;
        }
        else{
            $column = $id;
            $column_value = ':'.$id;
        }
    }

    $ids = array_keys($data);
    try {
        $stmt = $dbconn->prepare("INSERT INTO submitted_repository_new(".$column.") VALUES (".$column_value.")");
        foreach($ids as $id){
            $stmt->bindparam(":" . $id, $data[$id]);

        }
        $stmt->execute();

        return $stmt;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}
function submit_repository_to_db()
{
    $result = process_user_input_to_db();
    $objDBController = new DBController();
    $dbconn = $objDBController->getConn();
    submit_repository($dbconn, $result);
}

function process_user_input(){
    $showing_label = get_repository_showing_label();
    $result = [];
    foreach (array_keys($_POST) as $key){
        if($key=='data_identifiers'&& sizeof($_POST[$key])>0){
            $new = $_POST[$key];
            if(isset($_POST['other_identifiers'])){
                $new[sizeof($new)-1]=$_POST['other_identifiers'];
            }
            array_push($result,[$showing_label[$key],implode(',',$new)]);
        }
        else if (strlen($_POST[$key]) > 0 &&array_key_exists($key, $showing_label)) {
            if($key == "datarepo_datatype" && $_POST["datarepo_datatype"] == "Other"){
                array_push($result,[$showing_label[$key],$_POST['otherDatatype']]);
            }
            elseif($key == "metadata_in_format" && $_POST["metadata_in_format"] == "Yes"){
                array_push($result,[$showing_label[$key],$_POST['metadata_format']]);
            }
            else{
                array_push($result,[$showing_label[$key],$_POST[$key]]);
            }
        }
    }
    return $result;
}
function process_user_input_to_db(){
    $result = [];
    foreach (array_keys($_POST) as $key){
        if($key=='data_identifiers'&& sizeof($_POST[$key])>0){
            $new = $_POST[$key];
            if(isset($_POST['other_identifiers'])){
                $new[sizeof($new)-1]=$_POST['other_identifiers'];
            }
            array_push($result,[$key,implode(',',$new)]);
        }
        else if (strlen($_POST[$key]) > 0 && in_array($key, get_repository_ids() )) {
            if($key == "datarepo_datatype" && $_POST["datarepo_datatype"] == "Other"){
                array_push($result,[$key,$_POST['otherDatatype']]);
            }

            elseif($key == "metadata_in_format" && $_POST["metadata_in_format"] == "Yes"){
                array_push($result,[$key,$_POST['metadata_format']]);
            }
            else{
                array_push($result,[$key,$_POST[$key]]);
            }
        }
    }
    return $result;
}
/*
 * construct message part in the email
 * @return array(string)
 */
function construct_message()
{
    $message = "";
    $result = process_user_input();
    foreach($result as $item){
        $message = $message . $item[0] . ':'.$item[1] . '<br>';

    }
    return $message;
}

/*
 * sendEmails function for submit_repository
 */
function sendEmails(){
    $subject = @$_POST['datarepo_name'];

    $from = @$_POST["submitter_email"];
    $to = array("XXXXXX@gmail.com");

    $body = 'DataMed submit repository request review<br>
    ----------------------------------------<br>
    MESSAGE: '.construct_message();

    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
        ->setUsername('XXXX@gmail.com')
        ->setPassword('XXXXXX');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance('bioCADDIE submit repo email:' . $subject)
        ->setFrom(array($from => 'bioCADDIE'))
        ->setTo($to)
        ->setBody($body)
        ->setContentType("text/html");
    $mailer->send($message);
}

function postToGitHub_repo_management(){
    $client = new \Github\Client();
    $client->authenticate('XXXX@gmail.com', 'XXXXX', Github\Client::AUTH_HTTP_PASSWORD);
    $client->api('issue')->create('biocaddie', 'submit-repository-management', array('title' => $_POST['datarepo_name'], 'body' => construct_message()));
    
}


?>