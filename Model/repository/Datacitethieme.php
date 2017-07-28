<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitethiemebRepository extends RepositoryBase {

    public $repoShowName = 'Thieme';
    public $wholeName = "Thieme&nbsp;Chemistry";
    public $id = '0069';
    public $source = "https://www.thieme.de";

    public $facetsFields = ['dataset.types.raw','dataset.availability.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.availability.raw'=>'Availability'
    ];
    public $index = 'datacitethieme';
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


    public $source_main_page = "https://www.thieme.de/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'Thieme Medical Publishers is a German medical and science publisher in the Thieme Publishing Group. It produces professional journals, textbooks, atlases, monographs and reference books in both German and English covering a variety of medical specialties, including neurosurgery, orthopaedics, endocrinology, radiology, anatomy, chemistry, otolaryngology, ophthalmology, audiology and speech language pathology, and complementary and alternative medicine. Thieme has more than 1,000 employees and maintains offices in seven cities worldwide, including New York City, Beijing, Delhi, Stuttgart, and three other cities in Germany.
';

}

?>