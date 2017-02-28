<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class NursaRepository extends RepositoryBase {

    public $repoShowName = 'NURSA';
    public $wholeName = 'Nuclear&nbsp;Receptor&nbsp;Signaling&nbsp;Atlas';
    public $id = '0020';
    public $source = "https://www.nursa.org/nursa/datasets/dataset.jsf?doi=";
    //public $searchFields = ['dataset.types','dataset.keywords', 'dataset.creators','dataset.ID','dataset.title','dataset.description',
    //                        'person.name','TaxonomicInformation.name','dataAcquisition.name'];
    public $facetsFields = ['dataAcquisition.name.raw','TaxonomicInformation.name.raw'];
    public $facetsShowName = [
        'dataAcquisition.name.raw'=>'Data Acquisition',
        'TaxonomicInformation.name.raw'=>'Taxonomic Information'
    ];
    public $index = 'nursadatasets';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.keywords', 'dataset.description','dataset.ID'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.keywords'=>'Keywords',
        'dataset.description'=>'Description',
        'dataset.ID'=>'ID'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Data Acquisition','Date Released'];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'dataAcquisition.name','dataset.dateReleased'];

    public $source_main_page = "https://www.nursa.org/nursa/index.jsf";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Nuclear Receptor Signaling Atlas (NURSA) was created to foster the development of a comprehensive understanding of the structure, function, and role in disease of nuclear receptors (NRs) and coregulators. NURSA seeks to elucidate the roles played by NRs and coregulators in metabolism and the development of metabolic disorders (including type 2 diabetes, obesity, osteoporosis, and lipid dysregulation), as well as in cardiovascular disease, oncology, regenerative medicine and the effects of environmental agents on their actions.';

}

?>