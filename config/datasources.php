<?php

// Import ElasticSearch library.
require_once dirname(__FILE__) . '/server.php';
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// ElasticSearch Configuration.
//$es_end_point = '129.106.149.72:9200';
//$es_end_point = '129.106.31.121:9200';
global $es_end_point;
// Set development server to local instance. 
if ($config_switch == 1) {
    $es_end_point = '127.0.0.1:9200';
}

// Initialize ElasticSearch and set the end point.
$es = new Elasticsearch\Client([
    'hosts' => [$es_end_point]
        ]);

// Returns data types list.
function getDatatypes() {
    return ['Protein Structure', 'Phenotype', 'Gene Expression', 'Nucleotide Sequence','Unspecified','Clinical Trials','Imaging Data','Morphology','Proteomics Data','Physiological Signals'];
}

// Returns data types mapping to elastic search indexes.
function getDatatypesMapping() {
    return ['Protein Structure' => ['pdb'],
        'Phenotype' => ['phenodisco','mpd'],
        'Gene Expression' => ['geo',  'arrayexpress', 'gemma','nursadatasets'], //'geo',
        'Nucleotide Sequence' => ['sra'],
        'Unspecified' => ['lincs','bioproject','dryad','dataverse','niddkcr'],
        'Clinical Trials'=>['clinicaltrials','ctn'],
        'Imaging Data'=>['cvrg','neuromorpho','ctn','cia','openfmri'],
        'Morphology'=>['neuromorpho'],
        'Proteomics Data'=>['peptideatlas','proteomexchange','yped'],
        'Physiological Signals'=>['physiobank'],


    ];
}

// Returns data types mapping to elastic search indexes.
function getRepositoryIDMapping() {
    return ['0001' => 'phenodisco',
        '0002' => 'pdb',
        '0003' => 'geo',
        '0004' => 'lincs',
        '0005' => 'gemma',
        '0006' => 'arrayexpress',
        '0007' => 'sra',
        '0008'=> 'bioproject',
        '0009' => 'clinicaltrials',
        '0010'=>'dryad',
        '0011'=>'cvrg',
        '0012'=>'dataverse',
        '0013'=>'neuromorpho',
        '0014'=>'peptideatlas',
        '0015'=>'ctn',
        '0016'=>'cia',
        '0017'=>'mpd',
        '0018'=>'niddkcr',
        '0019'=>'openfmri',
        '0020'=>'nursadatasets',
        '0021'=>'physiobank',
        '0022'=>'proteomexchange',
        '0023'=>'yped',
    ];
}


// Map repository ID to repository name
function getRepositoryIDNameMapping() {
    return ['0001' => 'dbGaP',
        '0002' => 'PDB',
        '0003' => 'GEO',
        '0004' => 'LINCS',
        '0005' => 'GEMMA',
        '0006' => 'ArrayExpress',
        '0007' => 'SRA',
        '0008'=> 'BioProject',
        '0009' => 'ClinicalTrials',
        '0010'=>'Dryad',
        '0011'=>'cvrg',
        '0012'=>'Dataverse',
        '0013'=>'NeuroMorpho.Org',
        '0014'=>'PeptideAtlas',
        '0015'=>'CTN',
        '0016'=>'CIA',
        '0017'=>'MPD',
        '0018'=>'NIDDKCR',
        '0019'=>'openfmri',
        '0020'=>'NURSA',
        '0021'=>'PhysioBank',
        '0022'=>'Proteomexchange',
        '0023'=>'YPED',
    ];
}

// Returns data types list.
function getElasticSearchIndexes() {

    return 'pdb'.',' . 'geo'.',' . 'phenodisco' .','. 'lincs' .','. 'arrayexpress'.','. 'gemma'.',' . 'sra'.','.'bioproject' .','.'clinicaltrials'.',' . 'dryad' .','
          .'cvrg'.','.'dataverse' .','.'neuromorpho'.','.'peptideatlas'.','.'ctn'.','.'cia'.','.'mpd'.','.'niddkcr'.','.'physiobank'.','.'proteomexchange'.','.'openfmri'.','.'nursadatasets'.','
          .'yped';

}

// Returns a list of accessibility types.
function getAllAccess() {
    return ['download', 'remoteAccess', 'remoteService', 'enclave','notAvailable'];
}

// Returns accessibility types mapping to elastic search indexes.
function getAccessibilityMapping() {
    return ['download' => ['phenodisco','pdb','geo','lincs','gemma',
                            'arrayexpress','sra','bioproject','clinicaltrials','dryad',
                            'cvrg','dataverse','neuromorpho','peptideatlas','ctn',
                            'cia','mpd', 'niddkcr','openfmri','nursadatasets',
                            'physiobank','proteomexchange','yped'],
        'remoteAccess' => [],
        'remoteService' => ['openfmri'],
        'enclave' => [],
        'notAvailable' => [],
    ];
}

// Return a list of authorization types
function getAllAuth(){
    return ['none','clickLicense','registration','duaIndividual','duaInstitution'];
}

// Return authorization types mapping to elasticsearch indexes.
function getAuthMapping(){
    return [
        'none'=>['phenodisco','pdb','geo','lincs','gemma',
                'arrayexpress','sra','bioproject','clinicaltrials','dryad',
                'cvrg','neuromorpho','peptideatlas','cia','mpd',
                'openfmri','nursadatasets','physiobank','proteomexchange','yped'],
        'clickLicense'=>['dataverse'],
        'registration'=>['ctn','niddkcr'],
        'duaIndividual'=>[],
        'duaInstitution'=>[]
    ];
}

