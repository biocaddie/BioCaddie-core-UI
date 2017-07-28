<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class NdarpapersRepository extends RepositoryBase {

    public $id = '0068';
    public $index = 'ndarpapers';
    public $type = 'dataset';
    public $repoShowName = 'Ndar Papers';

    public $wholeName = '';

    /**
     * Specifies the fields ElasticSearch uses to run the search.
     * @var array(string)
     */
   // public $searchFields = [ 'dataset.ID','dataset.creators','dataset.description', 'dataset.keywords','dataset.title','dataset.types',
   //                         'taxonomicInformation.name','dataAcquisition.ID','dataAcquisition.name'];
    /**
     *  Specifies the list of fields to be used for facets' filtering.
     * @var array(string)
     */
    public $facetsFields = ['dataset.types.raw'];
    /**
     *  Indicates the list of display values for facets' filtering.
     * @var array(key(string), value(string))
     */
    public $facetsShowName = [
        'dataset.types.raw'=>'Data Type',

       ];


    /**
     * Specifies the list of displayed value for headers in "search.php" page.
     * @var array(string)
     */
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.description','dataset.types'];

    /**
     * Specifies a list of fields displayed in "search.php" page.
     * @var array(string)
     */
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'dataset.description'=>'Description',
        'dataset.types'=>'Types'
    ];

    /**
     * Specifies a list of fields displayed in "search-repository.php" page.
     * @var array(string)
     */
    public $searchRepoHeader = ['Title', 'ID', 'Description'];

    /**
     * Specifies a list of display values for headers in "search-repository.php" page.
     * @var array(string)
     */
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.description'];

    //display-item page
    /**
     * Specifies the list of fields displayed in "display-item.php" page.
     * @var array(string)
     */

    public $sort_field = '';
    public $description = 'The National Database for Autism Research (NDAR) is an NIH-funded research data repository that aims to accelerate progress in autism spectrum disorders (ASD) research through data sharing, data harmonization, and the reporting of research results. NDAR also serves as a scientific community platform and portal to multiple other research repositories, allowing for aggregation and secondary analysis of data.';


}

?>