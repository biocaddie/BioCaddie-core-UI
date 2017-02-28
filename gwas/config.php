<?php

//for ES end point config, when change ES enpoint, please change here
require_once '../vendor/autoload.php';
require_once "../config/config.php";

global $es_end_point;

$es = new Elasticsearch\Client([
     'hosts' => [$es_end_point]
        ]);

//for datatype facets,when add more repository, please change here
$datatype_index = ['protein' => 'pdb_v2',
    'phenotype' => 'phenodisco',
    'gene_expression' => 'geo',
    'sequence' => 'sra'
];

$es_index = 'pdb_v2,geo,phenodisco,sra';

$datatypes = ['protein', 'phenotype', 'gene expression', 'sequence'];
?>