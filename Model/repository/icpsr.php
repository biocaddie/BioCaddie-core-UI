<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class IcpsrRepository extends RepositoryBase {

    public $id = '0025';
    public $index = 'icpsr';
    public $type = 'dataset';
    public $repoShowName = 'ICPSR';

    public $wholeName = 'Interuniversity Consortium for Political and Social Research';

    /**
     * Specifies the fields ElasticSearch uses to run the search.
     * @var array(string)
     */
  //  public $searchFields = [ 'dataset.ID','dataset.creators','dataset.description', 'dataset.keywords','dataset.title','dataset.types',
  //                           'citations.title','citation.authors'];
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
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.description','dataset.creators'];

    /**
     * Specifies a list of fields displayed in "search.php" page.
     * @var array(string)
     */
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'dataset.description'=>'Description',
        'dataset.creators'=>'Creators'
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
    public $description = 'ICPSR maintains a data archive of more than 250,000 files of research in the social and behavioral sciences. It hosts 21 specialized collections of data in education, aging, criminal justice, substance abuse, terrorism, and other fields.';

}

?>