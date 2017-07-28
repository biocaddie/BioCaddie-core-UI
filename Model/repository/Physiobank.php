<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class PhysiobankRepository extends RepositoryBase
{

    public $repoShowName = 'PhysioBank';
    public $wholeName = '';
    public $id = '0021';
    public $source = "http://physionet.org/physiobank/";
    //public $searchFields = ['dataset.description', 'dataset.title','dataset.types','dataset.creators', 'primaryPublication.formattedCitation'];
    public $facetsFields = ['dataset.types.raw'];
    public $facetsShowName = [
        'dataset.types.raw' => 'Data Type',

    ];
    public $index = 'physiobank';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.description', 'dataset.types'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.description' => 'Description',
        'dataset.types' => 'Data Type',

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Datatype'];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'dataset.types',];


    public $source_main_page = "http://physionet.org/physiobank/";
    public $sort_field = '';
    public $description = 'PhysioBank is a large and growing archive of well-characterized digital recordings of physiologic signals and related data for use by the biomedical research community. PhysioBank currently includes databases of multi-parameter cardiopulmonary, neural, and other biomedical signals from healthy subjects and patients with a variety of conditions with major public health implications, including sudden cardiac death, congestive heart failure, epilepsy, gait disorders, sleep apnea, and aging.';



}

?>