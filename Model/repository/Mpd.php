<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';


class MpdRepository extends RepositoryBase {

    public $repoShowName = 'MPD';
    public $wholeName = 'Mouse&nbsp;Phenome&nbsp;Database';
    public $id = '0017';
    public $source = "http://phenome.jax.org/";
  //  public $searchFields = ['dataset.ID','dataset.creators','dataset.title','dataset.description','dataset.types','dimension.name','primaryPublication.authors','taxonomicInformation.name','taxonomicInformation.strain'];
    public $facetsFields = ['taxonomicInformation.name','dataset.size','taxonomicInformation.strain'];
    public $facetsShowName = [
        'taxonomicInformation.name'=>'Taxonomic Information',
        'dataset.size' => 'Dataset Size',
        'taxonomicInformation.strain'=> 'Strain'
    ];
    public $index = 'mpd';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.types','dataset.size' ,'dataset.description'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'dataset.types'=>'Data Type',
        'dataset.size'=>'Size'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Data Type','Size' ];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'dataset.types','dataset.size'];

    public $link_field = 'dataset.title';
    public $source_main_page = "http://phenome.jax.org/";
    public $sort_field = '';
    public $description = 'The Mouse Phenome Database (MPD) has characterizations of hundreds of strains of laboratory mice to facilitate translational discoveries and to assist in selection of strains for experimental studies.';

    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        unset($search_results['datasetDistributions']);
        return $search_results;
    }

}

?>