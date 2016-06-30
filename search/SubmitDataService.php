<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 5/10/16
 * Time: 10:48 AM
 */
require_once dirname(__FILE__) . '/../config/config.php';
require_once dirname(__FILE__) . '/../dbcontroller.php';

function get_showing_label()
{
    $showing_label = [
        "submitter_name" => "Submitter's Name",
        "submitter_email" => "Submitter Email",
        "submitter_organization" => "Submitter's Organization",
        "submitter_address" => "Submitter's Address",
        'dataset_title' => 'Dataset Title',
        'dataset_description' => 'Dataset Description',
        "dataset_identifier" => "Dataset Identifier",
        'dataset_idscheme' => 'Identifier Scheme',
        "dataset_datatype" => "Dataset datatype",
        "dataset_size" => "Size",
        "dataset_downloadurl" => "Dataset download URL",
        'dataset_version' => 'Version',
        "dataset_date" => "Dataset Release Date",
        "dataset_license" => "License",

        "study_title" => "Study Title",
        "study_studytype" => "Study Type",
        "study_keywords" => "Study Keywords",

        "organism_name" => "Organism Name",
        "organism_strain" => "Organism Strain",

        "publication_title" => "Publication Title",
        "publication_journal" => "Publication Journal",
        "publication_authors" => "Publicatio Authors",
        "publication_pmid" => "Pubmed ID",
        'publication_date' => 'Publication Date',

        "datastandard_name" => "Data Standard name",
        "datastandard_homepage" => "Data Standard Homepage",
        "datastandard_id" => "Data Standard Identifier",
        "datastandard_idscheme" => "Data Standard Identifier Scheme",

        "datarepo_name" => "Repository Name",
        "datarepo_abbr" => "Repository Abbreviation",
        "datarepo_id" => "Repostitory ID",
        "datarepo_homepage" => "Repostitory Homepage",

        "grant_name" => "Grant Name",
        "grant_funder" => "Grant Funder",
        "grant_id" => "Grant ID",

        "organization_name" => "Organization Name",
        "organization_homepage" => "Organization Homepage",
        "organization_abbr" => "Organization Abbreviation"
    ];
    return $showing_label;
}
function get_ids(){
    $ids = [
        "submitter_name",
        "submitter_email",
        "submitter_organization",
        "submitter_address",
        'dataset_title',
        'dataset_description',
        "dataset_identifier",
        'dataset_idscheme',
        "dataset_datatype",
        "dataset_size",
        "dataset_downloadurl",
        'dataset_version',
        "dataset_date",
        "dataset_license",

        "study_title",
        "study_studytype",
        "study_keywords",

        "organism_name",
        "organism_strain",

        "publication_title",
        "publication_journal",
        "publication_authors",
        "publication_pmid",
        'publication_date',

        "datastandard_name",
        "datastandard_homepage",
        "datastandard_id",
        "datastandard_idscheme",

        "datarepo_name",
        "datarepo_abbr",
        "datarepo_id",
        "datarepo_homepage",

        "grant_name",
        "grant_funder",
        "grant_id",

        "organization_name",
        "organization_homepage",
        "organization_abbr"
    ];
    return $ids;
}
function get_repository_showing_label()
{
    $showing_label = [
        "submitter_name" => "Submitter's Name",
        "submitter_email" => "Submitter Email",
        "submitter_organization" => "Submitter's Organization",
        "submitter_address" => "Submitter's Address",

        "datarepo_name" => "Repository Name",
        "datarepo_abbr" => "Repository Abbreviation",
        "datarepo_homepage" => "Repostitory Homepage",
        "datarepo_datatype"=> "Repostitory Datatype",
        "datarepo_license"=> "Repostitory License",
        "datarepo_version"=> "Repostitory Version",
        "datarepo_size"=> "Repostitory Size",

        'contact_people'=> "Contact People",
        'contact_email'=> "Contact Email",

        'sample_question'=> "Can you provide a sample metadata file of the dataset?",
        'xml_question'=> "Do you have a XML file which describes the metadata scheme?",
        'work_question'=> "Do you have time to work with us?"
    ];
    return $showing_label;
}
function get_repository_ids(){
    $ids = [
        "submitter_name",
        "submitter_email",
        "submitter_organization",
        "submitter_address",

        "datarepo_name",
        "datarepo_abbr",
        "datarepo_homepage",
        "datarepo_datatype",
        "datarepo_license",
        "datarepo_version",
        "datarepo_size",

        'contact_people',
        'contact_email',

        'sample_question',
        'xml_question',
        'work_question'
    ];
    return $ids;
}

//for submit_data.php page
function submit($dbconn, $data)
{
    $ids = get_ids();
    $column = '';
    $column_value = '';
    foreach($ids as $id){

        if(strlen($column)>0) {
            $column = $column . ',' . $id;
            $column_value = $column_value . ',' . ':'.$id;
        }
        else{
            $column = $id;
            $column_value = ':'.$id;
        }
    }

    try {

        $stmt = $dbconn->prepare("INSERT INTO submitted_data(".$column.") VALUES (".$column_value.")");
        foreach($ids as $id){
            if($id=='dataset_datatype' && $data[$id]=='Other'){
                $stmt->bindparam(":".$id, $data["otherDatatype"]);
            }
            else {
                $stmt->bindparam(":" . $id, $data[$id]);
            }
        }
        $stmt->execute();

        return $stmt;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}
function show_submitted($objDBController,$selectItems){
    $query_map = [
        "all"=>"Select * from submitted_data",
        "approved"=>"Select * from submitted_data WHERE approve_status = 'Yes'",
        "disapproved"=>"Select * from submitted_data WHERE approve_status = 'No'",
        "notreviewed"=>"Select * from submitted_data WHERE (approve_status ='' or approve_status is NULl)"
    ];
    $query = $query_map[$selectItems];
    $dbconn=$objDBController->getConn();
    $stmt = $dbconn->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    $column = get_ids();
    $result_arrays=[];
    foreach($results as $result){
        $result_array=[];
        foreach(array_keys($result) as $key){
            if($key=='approve_status'){
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
function show_submitted_repository($objDBController,$selectItems){
    $query_map = [
        "all"=>"Select * from submitted_repository",
        "reviewed"=>"Select * from submitted_repository WHERE reviewed = 'Yes'",
        "notreviewed"=>"Select * from submitted_repository  WHERE (reviewed ='' or reviewed is NULl)"
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
//for manage_submit_data.php page
function change_approve_to_db($dbconn,$data)
{

    foreach (array_keys($data) as $key) {
        if (substr($key, 0, 8) == 'approve_') {
            $id = substr($key, 8);
            $status = $data[$key];
            if ($data[$key] == 'Yes' || $data[$key] == 'No') {
                try {
                    $stmt = $dbconn->prepare("UPDATE submitted_data SET approve_status=? where ID=?");
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
function change_review_to_db($dbconn,$data)
{

    foreach (array_keys($data) as $key) {
        if (substr($key, 0, 7) == 'review_') {
            $id = substr($key, 7);
            $status = $data[$key];
            if ($data[$key] == 'Yes') {
                try {
                    $stmt = $dbconn->prepare("UPDATE submitted_repository SET reviewed=? where ID=?");
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

function submit_repository($dbconn, $data)
{
    $ids = get_repository_ids();

    $column = '';
    $column_value = '';
    foreach($ids as $id){

        if(strlen($column)>0) {
            $column = $column . ',' . $id;
            $column_value = $column_value . ',' . ':'.$id;
        }
        else{
            $column = $id;
            $column_value = ':'.$id;
        }
    }

    try {

        $stmt = $dbconn->prepare("INSERT INTO submitted_repository(".$column.") VALUES (".$column_value.")");
        foreach($ids as $id){
            if($id=='datarepo_datatype' && $data[$id]=='Other'){
                $stmt->bindparam(":".$id, $data["otherDatatype"]);
            }
            else {
                $stmt->bindparam(":" . $id, $data[$id]);
            }
        }
        $stmt->execute();

        return $stmt;
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

?>