<?php
error_reporting(0);

//for cookie security issue
/*ini_set("session.use_cookies", 0);
ini_set("session.use_only_cookies", 0);
ini_set("session.use_trans_sid", 1);
ini_set("session.cache_limiter", "");
*/
/*start session*/
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
date_default_timezone_set('America/chicago');

//Es endpoint
$es_end_point = 'XXX.XX.XX.XXX:9200';



//terminology server
$scigraph_url = "http://ipaddress/scigraph/graph/neighbors/";
$scigraph = "http://ipaddress/scigraph/";


//similarity config
$similarity_url = "http://localhost:8085/dataset%23";


//Mysql database config
$user = "username";
$password = "passwword";
$database = "database";
$dbconf =array(
	'ip' => "localhost", 
	'user'=>"username",
	'password'=> 'passwword',
	'database'=>'database',
);

//nlp server
$nlp_server = 'http://ipaddress/nlp-process-webapp/cdr';

$metamap_server = 'http://ipaddress/nlp-process-webapp-mm/cdr';

//IseeDelve
$IseeDelve = 'https://ipaddress/iseedelve/pdb_v2/';

//Tracking system
$logstash_url = "http://127.0.0.1:5042";

$cdedemo=false;
?>
