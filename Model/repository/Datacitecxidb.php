<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitecxidbRepository extends RepositoryBase {

    public $repoShowName = 'CXIDB';
    public $wholeName = 'Coherent&nbsp;X-ray&nbsp;Imaging&nbsp;Data&nbsp;Bank';
    public $id = '0047';
    public $source = "http://cxidb.org/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitecxidb';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Creators','Released Date','Description'];
    public $searchRepoField = ['dataset.title', 'dataset.creators','dataset.dateReleased','attributes.description' ];


    public $source_main_page = "http://cxidb.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'CXIDB is dedicated to further the goal of making data from Coherent X-ray Imaging (CXI) experiments available to all, as well as archiving it. The website also serves as the reference for the CXI file format, in which most of the experimental data on the database is stored in.
';

}

?>