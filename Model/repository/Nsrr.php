<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class NsrrRepository extends RepositoryBase {

    public $repoShowName = 'NSRR';
    public $wholeName = 'National&nbsp;Sleep&nbsp;Research&nbsp;Resource';
    public $id = '0064';
    public $source = "https://sleepdata.org/";

    public $facetsFields = ['study.type.raw'];
    public $facetsShowName = [
        'study.type.raw'=>'Study Type',
    ];
    public $index = 'nsrr';
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


    public $source_main_page = "https://sleepdata.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The National Sleep Research Resource (NSRR) offers free access to large collections of de-identified physiological signals and clinical data elements collected in well-characterized research cohorts and clinical trials.
                            The NSRR encourages interested researchers, educators, and trainees to join its community.
                            Members can contribute their own data and tools for sharing, provide information and feedback on ways to improve sleep and physiological signal data exchange and analysis,
                            and offer ideas on how to make NSRR and other resources work best for the scientific community.';

}

?>