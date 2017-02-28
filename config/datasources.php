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
    return ['Protein', 'Phenotype', 'Gene Expression', 'Nucleotide Sequence','Clinical Trials','Imaging Data','Morphology','Proteomics Data','Physiological Signals','Epigenetic Data','Data from Papers',
        'Omics Data','Survey Data','Cell Signaling','Unspecified',];
}

// Returns data types mapping to elastic search indexes.
function getDatatypesMapping() {
    return ['Protein' => ['pdb','swissprot','datacitesbgrid'],
        'Phenotype' => ['dbgap','mpd','datacitemorphobank','clinvar'],
        'Gene Expression' => ['geo',  'arrayexpress', 'gemma','nursadatasets'],
        'Nucleotide Sequence' => ['sra','datacitebgi'],
        'Morphology'=>['neuromorpho'],
        'Clinical Trials'=>['clinicaltrials','ctn'],
        'Proteomics Data'=>['peptideatlas','proteomexchange','yped'],
        'Physiological Signals'=>['physiobank'],
        'Epigenetic Data'=>['epigenomics'],
        'Data from Papers'=>['datacitepeerj'],

        'Omics Data'=>['omicsdi'],
        'Survey Data'=> ['datacitefdz'],
        'Cell Signaling'=>['datacitesdscsg'],
        'Imaging Data'=>['cvrg','neuromorpho','cia','openfmri','cil','bmrb','retina','emdb', 'nitrcir','neurovaultatlases','neurovaultcols','neurovaultnidm','datacitecxidb','datacitembf','datacitecandi'],
        'Unspecified' => ['lincs','bioproject','dryad','dataverse','niddkcr','icpsr','gdc','rgd','vectorbase','datacitegnd','datacitezenodo',
            'datacitecrcns','dataciteimmport','datacitedatabrary','datacitelshtm','datacitejhu',
            'datacitesimtk',
            'datacitebils',
            'dataciteada',
            'dataciteukda',
            'dataciteadaptive',
            'datacitemit',
            'datacitectsi',
            'datacitenimh',
            'nsrr'],
    ];
}

// Returns data types mapping to elastic search indexes.
function getRepositoryIDMapping() {
    return ['0001' => 'dbgap',
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
        '0026'=>'gdc',
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
        '0043'=>'datacitezenodo',
        '0044'=>'omicsdi',
        '0045'=>'datacitesbgrid',
        '0046'=>'datacitesimtk',
        '0047'=>'datacitecxidb',
        '0048'=>'datacitebils',
        '0049'=>'dataciteada',
        '0050'=>'dataciteukda',
        '0051'=>'dataciteadaptive',
        '0052'=>'datacitemit',
        '0053'=>'datacitectsi',
        '0054'=>'datacitefdz',
        '0055'=>'datacitembf',
        '0056'=>'datacitenimh',
        '0057'=>'datacitejhu',
        '0058'=>'datacitecandi',
        '0059'=>'datacitelshtm',
        '0060'=>'datacitedatabrary',
        '0061'=>'dataciteimmport',
        '0062'=>'datacitesdscsg',
        '0063'=>'datacitecrcns',
        '0064'=>'nsrr'
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
        '0026'=>'GDC',
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
        '0043'=>'Zenodo',
        '0044'=>'OmicsDI',
        '0045'=>'SBGrid',
        '0046'=>'SimTK',
        '0047'=>'CXIDB',
        '0048'=>'BILS',
        '0049'=>'ADA',
        '0050'=>'UKDA',
        '0051'=>'Adaptive Biotechnologies',
        '0052'=>'MITLCP',
        '0053'=>'UCSF-CTSI',
        '0054'=>'FDZ-DZA',
        '0055'=>'MBF',
        '0056'=>'NIMH',
        '0057'=>'JHUDMS',
        '0058'=>'CANDI',
        '0059'=>'LSHTM',
        '0060'=>'Databrary',
        '0061'=>'ImmPort',
        '0062'=>'NSGM',
        '0063'=>'CRCNS',
        '0064'=>'NSRR'
    ];
}

// Returns data types list.
function getElasticSearchIndexes() {

    return 'pdb'.',' . 'geo'.',' . 'dbgap' .','. 'lincs' .','. 'arrayexpress'.','. 'gemma'.',' . 'sra'.','.'bioproject' .','.'clinicaltrials'.',' . 'dryad' .','
          .'cvrg'.','.'dataverse' .','.'neuromorpho'.','.'peptideatlas'.','.'ctn'.','.'cia'.','.'mpd'.','.'niddkcr'.','.'physiobank'.','.'proteomexchange'.','.'openfmri'.','.'nursadatasets'.','
          .'yped'.','.'cil'.','.'icpsr'.','.'gdc'.','.'bmrb'.','.'swissprot'.','.'clinvar'.','.'retina'.','.'emdb'.','.'epigenomics'.','.'nitrcir'.','.'neurovaultatlases'.','.'neurovaultcols'.','
          .'neurovaultnidm'.','.'rgd'.','.'datacitebgi'.','.'datacitemorphobank'.','.'vectorbase'.','.'datacitegnd'.','.'datacitepeerj'.','.'datacitezenodo'.','.'omicsdi'.','.'datacitesbgrid'
          .','.'datacitesimtk'.','.'datacitecxidb'.','.'datacitebils'.','.'dataciteada'.','.'dataciteukda'.','.'dataciteadaptive'.','.'datacitemit'.','.'datacitectsi'.','.'datacitefdz'
          .','.'datacitembf'.','.'datacitenimh'.','.'datacitejhu'.','.'datacitecandi'.','.'datacitelshtm'.','.'datacitedatabrary'.','.'dataciteimmport'.','.'datacitesdscsg'.','.'datacitecrcns'
          .','.'nsrr';

}

// Returns a list of accessibility types.
function getAllAccess() {
    return ['download', 'remoteAccess', 'remoteService', 'enclave','notAvailable'];
}

// Returns accessibility types mapping to elastic search indexes.
function getAccessibilityMapping() {
    return ['download' => ['dbgap','pdb','geo','lincs','gemma',
                            'arrayexpress','sra','bioproject','dryad',
                            'cvrg','dataverse','neuromorpho','peptideatlas','ctn',
                            'cia','mpd', 'niddkcr','openfmri','nursadatasets',
                            'physiobank','proteomexchange','yped','cil','icpsr','gdc',
                            'swissprot','retina','emdb','epigenomics','nitrcir',
                            'neurovaultatlases','neurovaultcols','neurovaultnidm',
                            'rgd','datacitebgi','datacitemorphobank','vectorbase',
                            'datacitegnd','datacitepeerj','datacitezenodo','omicsdi','datacitesbgrid','datacitesimtk',
        'datacitecxidb','datacitebils','dataciteada','dataciteukda','dataciteadaptive','datacitemit','datacitectsi',
        'datacitefdz','datacitembf','datacitenimh','datacitejhu','datacitecandi','datacitelshtm','datacitedatabrary',
        'dataciteimmport','datacitesdscsg','datacitecrcns','nsrr'
    ],
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
        'none'=>['dbgap','pdb','geo','lincs','gemma',
                'arrayexpress','sra','bioproject','dryad',
                'cvrg','neuromorpho','peptideatlas','cia','mpd',
                'openfmri','nursadatasets','physiobank','proteomexchange',
                'yped','cil','gdc','swissprot','clinvar','retina','emdb','epigenomics',
                'nitrcir','neurovaultatlases','neurovaultcols','neurovaultnidm',
                'rgd','datacitebgi','datacitemorphobank','vectorbase','datacitegnd',
                'datacitepeerj','datacitezenodo','omicsdi','datacitesbgrid','datacitesimtk',
            'datacitecxidb','datacitebils','dataciteada','dataciteukda','dataciteadaptive',
            'datacitemit','datacitectsi','datacitefdz','datacitembf','datacitenimh','datacitejhu',
            'datacitecandi','datacitelshtm','datacitedatabrary','dataciteimmport','datacitesdscsg','datacitecrcns',
            'nsrr'],
        'clickLicense'=>['dataverse'],
        'registration'=>['ctn','niddkcr','cil','icpsr','gdc'],
        'duaIndividual'=>[],
        'duaInstitution'=>[]
    ];
}
