<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class GdcRepository extends RepositoryBase
{

    public $repoShowName = 'GDC';
    public $wholeName = 'Genomic&nbsp;Data&nbsp;Commons';
    public $id = '0026';
    public $index = 'gdc';
    public $type = 'dataset';

    public $source = "https://gdc-api.nci.nih.gov/files";

    public $facetsFields = ['disease.name.raw', 'anatomicalPart.name.raw'];
    public $facetsShowName = [
        'disease.name.raw' => 'Disease',
        'anatomicalPart.name.raw' => 'Primary Site'];


    public $searchPageField = [ 'dataset.ID','dataset.creator','disease.name','anatomicalPart.name'];
    public $searchPageHeader = [
        'dataset.creator' => 'Creator',
        'dataset.ID' => 'ID',
        'disease.name' => 'Disease',
        'anatomicalPart.name' => 'Primary Site'
    ];

    public $searchRepoHeader = [ 'ID','Creator','Disease','Primary Site','Diversity','EUR','SAS','AMR','EAS','AFR'];
    public $searchRepoField = [ 'dataset.ID','dataset.creator', 'disease.name','anatomicalPart.name','analyticsDiploid.score','analyticsDiploid.EUR',
        'analyticsDiploid.SAS','analyticsDiploid.SAS','analyticsDiploid.EAS','analyticsDiploid.AFR'];


    public $source_main_page = 'https://gdc.cancer.gov/';
    public $sort_field = 'dataset.dateCreated';
    public $description = 'The NCI\'s Genomic Data Commons (GDC) provides the cancer research community with a unified data repository that enables data sharing across cancer genomic studies in support of precision medicine.';

    public function show_table($results, $query)
    {   $show_array = parent::show_table($results, $query);
        for($i=0;$i<sizeof($show_array);$i++){
            $show_array[$i][4]=substr($show_array[$i][4],0,4);
            $show_array[$i][5]=substr($show_array[$i][5],0,4);
            $show_array[$i][6]=substr($show_array[$i][6],0,4);
            $show_array[$i][7]=substr($show_array[$i][7],0,4);
            $show_array[$i][8]=substr($show_array[$i][8],0,4);
            $show_array[$i][9]=substr($show_array[$i][9],0,4);
        }

        return $show_array;
    }

    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);
        unset($search_results['dataRepository']);
        for($i=0;$i<sizeof($search_results['organization']);$i++){
            if($search_results['organization'][$i][0]==='homePage'){
                $search_results['organization'][$i][2] = $search_results['organization'][$i][1];
            }
        }
        return $search_results;
    }

}

?>
