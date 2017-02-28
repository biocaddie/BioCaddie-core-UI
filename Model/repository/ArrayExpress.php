<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class ArrayExpressRepository extends RepositoryBase {

    public $id = '0006';
    public $index = 'arrayexpress';
    public $type = 'dataset';
    public $repoShowName = 'ArrayExpress';

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
    public $facetsFields = ['dataset.types.raw','dataset.keywords.raw'];
    /**
     *  Indicates the list of display values for facets' filtering.
     * @var array(key(string), value(string))
     */
    public $facetsShowName = [
        'dataset.types.raw'=>'Data Type',
        'dataset.keywords.raw'=>'Keywords'
       ];


    /**
     * Specifies the list of displayed value for headers in "search.php" page.
     * @var array(string)
     */
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.description'];

    /**
     * Specifies a list of fields displayed in "search.php" page.
     * @var array(string)
     */
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'dataset.description'=>'Description',
    ];

    /**
     * Specifies a list of fields displayed in "search-repository.php" page.
     * @var array(string)
     */
    public $searchRepoHeader = ['Title', 'ID', 'Description', 'Date Released'];

    /**
     * Specifies a list of display values for headers in "search-repository.php" page.
     * @var array(string)
     */
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.description', 'dataset.dateReleased'];

    //display-item page
    /**
     * Specifies the list of fields displayed in "display-item.php" page.
     * @var array(string)
     */

    public $sort_field = 'dataset.dateReleased';
    public $description = 'ArrayExpress Archive of Functional Genomics Data stores data from high-throughput functional genomics experiments, and provides these data for reuse to the research community.';


}

?>