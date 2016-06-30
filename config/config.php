<?php

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

$es_end_point = '129.106.31.121:9200';
//$es_end_point = '129.106.149.72:9200';

//terminology server
$scigraph_url = "http://localhost:9000/scigraph/graph/neighbors/";
$scigraph = "http://localhost:9000/scigraph/";

//similarity config
$similarity_url = "http://localhost:8085/dataset%23";


//Mysql database config
$user = "biocaddie";
//$password = "b10c6dd13";
$password = "biocaddie";
$database = "biocaddie";
$dbconf =array(
		'ip' => "129.106.31.121",  // "192.168.224.106",
		'user'=>"biocaddie",
		'password'=> 'biocaddie',
		'database'=>'biocaddie',
);

//nlp server
$nlp_server = 'http://clamp.uth.edu/nlp-process-webapp/cdr';

//IseeDelve
$IseeDelve = 'http://datamed.biocaddie.org/iseedelve/pdb_v2/';
?>


