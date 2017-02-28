<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class RetinaRepository extends RepositoryBase {

    public $repoShowName = 'Retina';
    public $wholeName = 'The&nbsp;Retina&nbsp;Project';
    public $id = '0030';
    public $source = "http://www.gensat.org/retina.jsp";
    //public $searchFields = ['dataset.ID','Study.output','Study.selectionCriteria','BiologicalEntity.name','MolecularEntity.name'];
    public $facetsFields = ['study.output.raw','biologicalEntity.name.raw','molecularEntity.name.raw'];
    public $facetsShowName = [
        'study.output.raw'=>'Study Output',
        'biologicalEntity.name.raw'=>'Biological Entity',
        'molecularEntity.name.raw' => 'Molecular Entity'
    ];
    public $index = 'retina';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.ID', 'biologicalEntity.name', 'molecularEntity.name'];
    public $searchPageHeader = [
        'dataset.ID'=>'ID',
        'biologicalEntity.name'=>'Biological Entity',
        'molecularEntity.name'=>'Molecular Entity',
    ];

    //search-repository page
    public $searchRepoHeader = ['ID', 'Study Output', 'Study Selection Criteria','Molecular Entity','Biological Entity'];
    public $searchRepoField = ['dataset.ID', 'study.output', 'study.selectionCriteria','molecularEntity.name','biologicalEntity.name' ];


    public $source_main_page = "http://www.gensat.org/retina.jsp";
    public $sort_field = '';
    public $description = 'The retina project is a collaboration between GENSAT project and the Roska Lab at FMI, Basel.The project and methods are described in Siegert et al. (Nature Neuroscience, 2009)';
    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        for($i=0;$i<sizeof($search_results['organization']);$i++){
            if($search_results['organization'][$i][0]=='homePage'){
                $search_results['organization'][$i][2]=$search_results['organization'][$i][1];
            }
        }

        return $search_results;
    }
}

?>