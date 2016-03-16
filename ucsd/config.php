<?php

//for cookie security issue
ini_set("session.use_cookies", 0);
ini_set("session.use_only_cookies", 0);
ini_set("session.use_trans_sid", 1);
ini_set("session.cache_limiter", "");

session_name('SessionName');

//for ES end point config, when change ES enpoint, please change here
require_once '../vendor/autoload.php';
/*$es = new Elasticsearch\Client([
			//'hosts'=>['127.0.0.1:9200']
			'hosts'=>['129.106.149.72:9200']
			]);*/

$params = array();
$params['connectionClass'] = '\Elasticsearch\Connections\CurlMultiConnection';

$params['hosts'] = array (
    'https://data.biocaddie.org:443'
);
$params['connectionParams']['curlOpts'] = array(
CURLOPT_TIMEOUT_MS=>50000,
CURLOPT_CONNECTTIMEOUT_MS => 50000,
   
);

$es = new Elasticsearch\Client($params);

//for datatype facets,when add more repository, please change here
/*$datatype_index = ['protein'=>'pdb_v2',
                   'phenotype'=>'phenodisco',
                   'gene_expression'=>'geo',
                   'gene expression'=>'geo',
                   'sequence'=>'sra'
                   ];

$es_index = 'pdb_v2,geo,phenodisco,sra';

$datatypes = ['protein','phenotype','gene expression','sequence'];*/
$datatype_index = ['protein'=>'biocaddie',
                   
                   ];

$es_index = 'biocaddie';

$datatypes = ['protein'];


?>