<?php

// Import ElasticSearch library.
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';

// ElasticSearch Configuration.
//$es_end_point = '129.106.149.72:9200';
//$es_end_point = '129.106.31.121:9200';

// Initialize ElasticSearch and set the end point.
$es = new Elasticsearch\Client([
    'hosts' => [$es_end_point]
        ]);

// Returns data types list.
function getDatatypes() {
    return ['Protein Structure', 'Phenotype', 'Gene Expression', 'Nucleotide Sequence','Unspecified','Clinical Trials','Imaging Data','Morphology','Proteomics Data','Physiological Signals','Epigenetic Data','Data from Papers'];
}

// Returns data types mapping to elastic search indexes.
function getDatatypesMapping() {
    return ['Protein Structure' => ['pdb','swissprot'],
        'Phenotype' => ['phenodisco','mpd','datacitemorphobank','clinvar'],
        'Gene Expression' => ['geo',  'arrayexpress', 'gemma','nursadatasets'],
        'Nucleotide Sequence' => ['sra','datacitebgi'],
        'Morphology'=>['neuromorpho'],
        'Clinical Trials'=>['clinicaltrials','ctn'],
        'Proteomics Data'=>['peptideatlas','proteomexchange','yped'],
        'Physiological Signals'=>['physiobank'],
        'Epigenetic Data'=>['epigenomics'],
        'Data from Papers'=>['datacitepeerj'],
        'Unspecified' => ['lincs','bioproject','dryad','dataverse','niddkcr','icpsr','tcga','rgd','vectorbase','datacitegnd','datacitezenodo'],
        'Imaging Data'=>['cvrg','neuromorpho','cia','openfmri','cil','bmrb','retina','emdb', 'nitrcir','neurovaultatlases','neurovaultcols','neurovaultnidm'],
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
        '0024'=>'cil',
        '0025'=>'icpsr',
        '0026'=>'tcga',
        '0027'=>'bmrb',
        '0028'=>'swissprot',
        '0029'=>'clinvar',
        '0030'=>'retina',
        '0031'=>'emdb',
        '0032'=>'epigenomics',
        '0033'=>'nitrcir',
        '0034'=>'neurovaultatlases',
        '0035'=>'neurovaultcols',
        '0036'=>'neurovaultnidm',
        '0037'=>'rgd',
        '0038'=>'datacitebgi',
        '0039'=>'datacitemorphobank',
        '0040'=>'vectorbase',
        '0041'=>'datacitegnd',
        '0042'=>'datacitepeerj',
        '0043'=>'datacitezenodo'
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
        '0016'=>'TCIA',
        '0017'=>'MPD',
        '0018'=>'NIDDKCR',
        '0019'=>'openfmri',
        '0020'=>'NURSA',
        '0021'=>'PhysioBank',
        '0022'=>'ProteomeXchange',
        '0023'=>'YPED',
        '0024'=>'CIL',
        '0025'=>'ICPRS',
        '0026'=>'TCGA',
        '0027'=>'BMRB',
        '0028'=>'UniProt:Swiss-Prot ',
        '0029'=>'ClinVar',
        '0030'=>'Retina',
        '0031'=>'PDBe:EMDB',
        '0032'=>'Epigenomics',
        '0033'=>'nitrcir',
        '0034'=>'NeuroVault:Atlases',
        '0035'=>'NeuroVault:Cols',
        '0036'=>'NeuroVault: NIDM',
        '0037'=>'RGD',
        '0038'=>'BGI',
        '0039'=>'MorphoBank',
        '0040'=>'VectorBase',
        '0041'=>'GND',
        '0042'=>'PeerJ',
        '0043'=>'datacitezenodo'
    ];
}

// Returns data types list.
function getElasticSearchIndexes() {

    return 'pdb'.',' . 'geo'.',' . 'phenodisco' .','. 'lincs' .','. 'arrayexpress'.','. 'gemma'.',' . 'sra'.','.'bioproject' .','.'clinicaltrials'.',' . 'dryad' .','
          .'cvrg'.','.'dataverse' .','.'neuromorpho'.','.'peptideatlas'.','.'ctn'.','.'cia'.','.'mpd'.','.'niddkcr'.','.'physiobank'.','.'proteomexchange'.','.'openfmri'.','.'nursadatasets'.','
          .'yped'.','.'cil'.','.'icpsr'.','.'tcga'.','.'bmrb'.','.'swissprot'.','.'clinvar'.','.'retina'.','.'emdb'.','.'epigenomics'.','.'nitrcir'.','.'neurovaultatlases'.','.'neurovaultcols'.','
          .'neurovaultnidm'.','.'rgd'.','.'datacitebgi'.','.'datacitemorphobank'.','.'vectorbase'.','.'datacitegnd'.','.'datacitepeerj'.','.'datacitezenodo';

}

// Returns a list of accessibility types.
function getAllAccess() {
    return ['download', 'remoteAccess', 'remoteService', 'enclave','notAvailable'];
}

// Returns accessibility types mapping to elastic search indexes.
function getAccessibilityMapping() {
    return ['download' => ['phenodisco','pdb','geo','lincs','gemma',
                            'arrayexpress','sra','bioproject','dryad',
                            'cvrg','dataverse','neuromorpho','peptideatlas','ctn',
                            'cia','mpd', 'niddkcr','openfmri','nursadatasets',
                            'physiobank','proteomexchange','yped','cil','icpsr','tcga',
                            'swissprot','retina','emdb','epigenomics','nitrcir',
                            'neurovaultatlases','neurovaultcols','neurovaultnidm',
                            'rgd','datacitebgi','datacitemorphobank','vectorbase',
                            'datacitegnd','datacitepeerj','datacitezenodo'],
        'remoteAccess' => [],
        'remoteService' => ['openfmri'],
        'enclave' => [],
        'notAvailable' => ['clinvar'],
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
                'arrayexpress','sra','bioproject','dryad',
                'cvrg','neuromorpho','peptideatlas','cia','mpd',
                'openfmri','nursadatasets','physiobank','proteomexchange',
                'yped','cil','tcga','swissprot','clinvar','retina','emdb','epigenomics',
                'nitrcir','neurovaultatlases','neurovaultcols','neurovaultnidm',
                'rgd','datacitebgi','datacitemorphobank','vectorbase','datacitegnd',
                'datacitepeerj','datacitezenodo'],
        'clickLicense'=>['dataverse'],
        'registration'=>['ctn','niddkcr','cil','icpsr','tcga'],
        'duaIndividual'=>[],
        'duaInstitution'=>[]
    ];
}
