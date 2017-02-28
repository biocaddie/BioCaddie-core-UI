<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class NeuromorphoRepository extends RepositoryBase {

    public $repoShowName = 'NeuroMorpho.Org';
    public $wholeName ='';
    public $id = '0013';
    public $source = "http://neuromorpho.org/neuron_info.jsp?neuron_name=";


  //  public $searchFields = ['dataset.ID','dataset.title', 'dimension.name','taxonomicinformation.name','taxonomicinformation.strain','molecularEntity.name','anatomicalPart.name'];
    public $facetsFields = ['studyGroup.name','anatomicalPart.name','molecularEntity.name'];
    public $facetsShowName = [
        'studyGroup.name' => 'Study Group',
        'anatomicalPart.name'=>'Anatomical Part',
        'molecularEntity.name'=>'Molecular Entity'
    ];

    public $index = 'neuromorpho';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.types','dataset.ID','activity.name'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.types'=>'Type',
        'activity.name'=>'Activity',
        'dataset.ID'=>'ID'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Activity','Anatomical Part','Study Group'];
    public $searchRepoField = [ 'dataset.title', 'activity.name','anatomicalPart.name','studyGroup.name'];


    public $source_main_page = 'http://neuromorpho.org/';
    public $sort_field = '';
    public $description = 'NeuroMorpho.Org is a centrally curated inventory of digitally reconstructed neurons associated with peer-reviewed publications. It contains contributions from over 80 laboratories worldwide and is continuously updated as new morphological reconstructions are collected, published, and shared.';


    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        unset($search_results['datasetDistributions']);
        return $search_results;
    }

}

?>