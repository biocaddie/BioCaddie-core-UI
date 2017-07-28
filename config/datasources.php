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
        'Phenotype' => ['clinvar','dbgap','mpd','datacitemorphobank'],
        'Gene Expression' => ['geo',  'arrayexpress', 'gemma','nursadatasets','lsdb','genenetwork','gtexldacc'],
        'Nucleotide Sequence' => ['sra','datacitebgi'],
        'Morphology'=>['neuromorpho'],
        'Clinical Trials'=>['clinicaltrials','ctn'],
        'Proteomics Data'=>['peptideatlas','proteomexchange','yped'],
        'Physiological Signals'=>['physiobank'],
        'Epigenetic Data'=>['epigenomics'],
        'Data from Papers'=>['datacitepeerj','ndarpapers'],

        'Omics Data'=>['omicsdi'],
        'Survey Data'=> ['datacitefdz'],
        'Cell Signaling'=>['datacitesdscsg'],
        'Imaging Data'=>['cvrg','neuromorpho','cia','openfmri','cil','bmrb','retina','emdb', 'nitrcir','neurovaultatlases','neurovaultcols','neurovaultnidm','datacitecxidb','datacitembf','datacitecandi'],
        'Unspecified' => ['lincs','bioproject','dryad','dataverse','niddkcr','icpsr','gdc','rgd','vectorbase','datacitegnd','datacitezenodo',
            'datacitecrcns','immport','datacitedatabrary','datacitelshtm','datacitejhu',
            'simtk',
            'datacitebils',
            'dataciteada',
            'dataciteukda',
            'dataciteadaptive',
            'datacitemit',
            'datacitectsi',
            'datacitenimh',
            'nsrr','naturedata','datacitethieme','datacitefigshare','dataciteccdc','wormbase','metabolomics'],
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
        '0046'=>'simtk',
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
        '0061'=>'immport',
        '0062'=>'datacitesdscsg',
        '0063'=>'datacitecrcns',
        '0064'=>'nsrr',
        '0065'=>'naturedata',
        '0066'=>'lsdb',
        '0067'=>'genenetwork',
        '0068'=>'ndarpapers',
        '0069'=>'datacitethieme',
        '0070'=>'datacitefigshare',
        '0071'=>'dataciteccdc',
        '0072'=>'wormbase',
        '0073'=>'gtexldacc',
        '0074'=> 'metabolomics'

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
        '0038'=>'GigaDB',
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
        '0064'=>'NSRR',
        '0065'=>'NSD',
        '0066'=>'LSDB',
        '0067'=>'GeneNetwork',
        '0068'=>'Ndar Papers',
        '0069'=>"Thieme",
        '0070'=>'Figshare',
        '0071'=>'CCDC',
        '0072'=>'Wormbase',
        '0073'=>'GTex',
        '0074'=>'Metabolomics'
    ];
}

function getRepositoryNameIDMapping() {
    return ['dbGaP'=>'0001',
            'PDB'=>'0002',
            'GEO'=>'0003' ,
            'LINCS'=>'0004',
            'GEMMA'=>'0005',
            'ArrayExpress'=>'0006',
            'SRA'=>'0007',
            'BioProject'=> '0008',
            'ClinicalTrials'=>'0009',
            'Dryad'=>'0010',
            'cvrg'=>'0011',
            'Dataverse'=>'0012',
            'NeuroMorpho.Org'=>'0013',
            'PeptideAtlas'=>'0014',
            'CTN'=>'0015',
            'TCIA'=>'0016',
            'MPD'=>'0017',
            'NIDDKCR'=>'0018',
            'openfmri'=>'0019',
            'NURSA'=>'0020',
        'PhysioBank'=>'0021',
        'ProteomeXchange'=>'0022',
        'YPED'=>'0023',
        'CIL'=>'0024',
        'ICPRS'=>'0025',
        'GDC'=>'0026',
        'BMRB'=>'0027',
        'UniProt:Swiss-Prot'=>'0028',
        'ClinVar'=>'0029',
        'Retina'=>'0030',
        'PDBe:EMDB'=>'0031',
        'Epigenomics'=>'0032',
        'nitrcir'=>'0033',
        'NeuroVault:Atlases'=>'0034',
        'NeuroVault:Cols'=>'0035',
        'NeuroVault: NIDM'=>'0036',
        'RGD'=>'0037',
        'GigaDB'=>'0038',
        'MorphoBank'=>'0039',
        'VectorBase'=>'0040',
        'GND'=>'0041',
        'PeerJ'=>'0042',
        'Zenodo'=>'0043',
        'OmicsDI'=>'0044',
        'SBGrid'=>'0045',
        'SimTK'=>'0046',
        'CXIDB'=>'0047',
        'BILS'=>'0048',
        'ADA'=>'0049',
        'UKDA'=>'0050',
        'Adaptive Biotechnologies'=>'0051',
        'MITLCP'=>'0052',
        'UCSF-CTSI'=>'0053',
        'FDZ-DZA'=>'0054',
        'MBF'=>'0055',
        'NIMH'=>'0056',
        'JHUDMS'=>'0057',
        'CANDI'=>'0058',
        'LSHTM'=>'0059',
        'Databrary'=>'0060',
        'ImmPort'=>'0061',
        'NSGM'=>'0062',
        'CRCNS'=>'0063',
        'NSRR'=>'0064',
        'NSD'=>'0065',
        'LSDB'=>'0066',
        'GeneNetwork'=>'0067',
        'Ndar Papers'=>'0068',
        "Thieme"=>'0069',
        "Figshare"=>'0070',
        "CCDC"=>'0071',
        'Wormbase'=>'0072',
        'GTex' => '0073',
        'Metabolomics' => '0074'
    ];
}


// Returns data types list.
function getElasticSearchIndexes() {

    return 'pdb'.',' . 'geo'.',' . 'dbgap' .','. 'lincs' .','. 'arrayexpress'.','. 'gemma'.',' . 'sra'.','.'bioproject' .','.'clinicaltrials'.',' . 'dryad' .','
          .'cvrg'.','.'dataverse' .','.'neuromorpho'.','.'peptideatlas'.','.'ctn'.','.'cia'.','.'mpd'.','.'niddkcr'.','.'physiobank'.','.'proteomexchange'.','.'openfmri'.','.'nursadatasets'.','
          .'yped'.','.'cil'.','.'icpsr'.','.'gdc'.','.'bmrb'.','.'swissprot'.','.'clinvar'.','.'retina'.','.'emdb'.','.'epigenomics'.','.'nitrcir'.','.'neurovaultatlases'.','.'neurovaultcols'.','
          .'neurovaultnidm'.','.'rgd'.','.'datacitebgi'.','.'datacitemorphobank'.','.'vectorbase'.','.'datacitegnd'.','.'datacitepeerj'.','.'datacitezenodo'.','.'omicsdi'.','.'datacitesbgrid'
          .','.'simtk'.','.'datacitecxidb'.','.'datacitebils'.','.'dataciteada'.','.'dataciteukda'.','.'dataciteadaptive'.','.'datacitemit'.','.'datacitectsi'.','.'datacitefdz'
          .','.'datacitembf'.','.'datacitenimh'.','.'datacitejhu'.','.'datacitecandi'.','.'datacitelshtm'.','.'datacitedatabrary'.','.'immport'.','.'datacitesdscsg'.','.'datacitecrcns'
          .','.'nsrr'.','.'lsdb'.','.'naturedata'.','.'genenetwork'.','.'ndarpapers'.','.'datacitethieme'.','.'datacitefigshare'.','.'dataciteccdc'.','.'wormbase'.','.'gtexldacc'.','.'metabolomics';

}

// Returns a list of accessibility types.
function getAllAccess() {
    return ['download', 'remoteAccess', 'remoteService', 'enclave','notAvailable'];
}

// Returns accessibility types mapping to elastic search indexes.
function getAccessibilityMapping() {
    return ['download' => ['dbgap','pdb','geo','lincs','gemma',
                            'arrayexpress','sra','bioproject','dryad',
                            'cvrg','dataverse','neuromorpho','peptideatlas',
                            'cia','mpd', 'niddkcr','openfmri','nursadatasets',
                            'physiobank','proteomexchange','yped','cil','icpsr','gdc',
                            'swissprot','retina','emdb','epigenomics','nitrcir',
                            'neurovaultatlases','neurovaultcols','neurovaultnidm',
                            'rgd','datacitebgi','datacitemorphobank','vectorbase',
                            'datacitegnd','datacitepeerj','datacitezenodo','omicsdi','datacitesbgrid','simtk',
        'datacitecxidb','datacitebils','dataciteada','dataciteukda','dataciteadaptive','datacitemit','datacitectsi',
        'datacitefdz','datacitembf','datacitenimh','datacitejhu','datacitecandi','datacitelshtm','datacitedatabrary',
        'immport','datacitesdscsg','datacitecrcns','nsrr','naturedata','lsdb','genenetwork','ndarpapers','datacitethieme',
        'datacitefigshare','dataciteccdc','wormbase','gtexldacc','metabolomics'#'ctn','clinicaltrials'
    ],
        'remoteAccess' => [],
        'remoteService' => ['openfmri'],
        'enclave' => [],
        'notAvailable' => [],#'clinvar'],
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
                'yped','cil','gdc','swissprot','retina','emdb','epigenomics',
                'nitrcir','neurovaultatlases','neurovaultcols','neurovaultnidm',
                'rgd','datacitebgi','datacitemorphobank','vectorbase','datacitegnd',
                'datacitepeerj','datacitezenodo','omicsdi','datacitesbgrid','simtk',
            'datacitecxidb','datacitebils','dataciteada','dataciteukda','dataciteadaptive',
            'datacitemit','datacitectsi','datacitefdz','datacitembf','datacitenimh','datacitejhu',
            'datacitecandi','datacitelshtm','datacitedatabrary','immport','datacitesdscsg','datacitecrcns',
            'nsrr','lsdb','naturedata','genenetwork','datacitethieme','datacitefigshare','dataciteccdc','wormbase',
            'gtexldacc','metabolomics'#'clinicaltrials','clinvar'
        ],
        'clickLicense'=>['dataverse'],
        'registration'=>['niddkcr','cil','icpsr','gdc'],#'ctn',
        'duaIndividual'=>['ndarpapers'],
        'duaInstitution'=>['ndarpapers']
    ];
}

function get_keyword_define(){
    return [
        'remoteAccess'=>'Users may access the data in a secure remote environment.  Data may not be downloaded. ',
        'remoteService'=>'A user may request that a service or computation be applied to the data.  The remote site ensures that no protected information is identifiable in the product.  The product may be downloaded. ',
        'enclave'=>'Access is provided to approved users within a secure facility without remote access.',
        'clickLicense'=>'Users must agree to an online license agreement.',
        'registration'=>'Users must register before access is allowed.  Registration information may be verified.',
        'duaIndividual'=>'A data use agreement signed by the investigator is required.  Data use agreements may require additional information, such as a research plan and an IRB review.',
        'duaInstitution'=>"A data use agreement signed by the investigator's institution is required.  Data use agreements may require additional information, such as a research plan and an IRB review.",
        'simpleLogin'=>'Singlefactor login or the use of an authentication key or registered IP address is required.',
        'multiLogin'=>'Multiple-factor login using a combination of IP address, password protection, authentication key, or other forms of authentication.'
    ];
}