<?php

//for ES end point config, when change ES enpoint, please change here
require_once '../vendor/autoload.php';
$es = new Elasticsearch\Client([
    //'hosts'=>['127.0.0.1:9200']
    'hosts' => ['129.106.31.121:9200']
        ]);

//for datatype facets,when add more repository, please change here
$datatype_index = ['protein' => 'pdb_v2',
    'phenotype' => 'phenodisco',
    'gene_expression' => 'geo',
    'gene expression' => 'geo',
    'sequence' => 'sra'
];

$es_index = 'pdb_v2,geo,phenodisco,sra';

$datatypes = ['protein', 'phenotype', 'gene expression', 'sequence'];
?>