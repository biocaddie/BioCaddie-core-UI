<?php

require_once '../config/config.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';
use \Guzzle\Http\Client;

$logstash_array = array(
    "cookieID" => $_POST["cookieID"],
    "actionTime" => $_POST["actionTime"],
    "actionType" => $_POST["actionType"],
    "actionID" => $_POST["actionID"],
    "actionText" => $_POST["actionText"],
    "actionX" => $_POST["actionX"],
    "actionY" => $_POST["actionY"],
    "actionURL" => $_POST["actionURL"],
    "searchQuery" => "",
    "searchTotal" => "",
    "searchCurrent" => "",
    "searchType" => "",
    "searchRepoSel" => "",
    "searchRepoFacets" => "",
    "searchDataSelect" => "",
    "searchDataTypes" =>  "",
    "searchResults" => ""
);


$client = new Client();
$request = $client->post($logstash_url);
$request->setBody(json_encode($logstash_array),'application/json');
$request->setHeader('Content-Type','application/json');

$client->send($request);
