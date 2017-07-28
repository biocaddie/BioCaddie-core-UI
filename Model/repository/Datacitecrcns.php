<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitecrcnsRepository extends RepositoryBase {

    public $repoShowName = 'CRCNS';
    public $wholeName = 'Collaborative&nbsp;Research&nbsp;in&nbsp;Computational&nbsp;Neuroscience';
    public $id = '0063';
    public $source = "http://crcns.org/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitecrcns';
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


    public $source_main_page = "http://crcns.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'To enable concerted efforts in understanding the brain experimental data and other resources such as stimuli and analysis tools should be widely shared by researchers all over the world. To serve this purpose, this website provides a marketplace and discussion forum for sharing tools and data in neuroscience. Information about the aims and scope of this site is given in an article (PDF also available here) published in February, 2008 in the Journal Neuroinformatics.

To date we host experimental data sets of high quality that will be valuable for testing computational models of the brain and new analysis methods.  The data include physiological recordings from sensory and memory systems, as well as eye movement data. For information about a data set select the data set in Data Sets and then navigate to the "About" page.   In addition, this website hosts a forum for each data set and a general discussion forum.

This website and the sharing of the data sets is funded by the CRCNS (Collaborative Research in Computational Neuroscience) program which is described in the About link.
    ';

}

?>