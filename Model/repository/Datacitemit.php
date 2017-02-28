<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitemitRepository extends RepositoryBase {

    public $repoShowName = 'MITLCP';
    public $wholeName = 'MIT&nbsp;Laboratory&nbsp;of&nbsp;Computational&nbsp;Physiology';
    public $id = '0052';
    public $source = "http://lcp.mit.edu/";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'datacitemit';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Released Date','Creators'];
    public $searchRepoField = ['dataset.title', 'dataset.ID','dataset.dateReleased','dataset.creators' ];


    public $source_main_page = "http://lcp.mit.edu/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Laboratory for Computational Physiology (LCP), under the direction of Professor Roger Mark,
                            conducts research on improving health care through new and refined approaches to interpreting data.
                             Some of the group’s researchers have medical backgrounds; others have backgrounds in computer science,
                              electrical engineering, physics, or mathematics; and others have training that spans several of these disciplines.';

}

?>