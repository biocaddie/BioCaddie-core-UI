<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/RepositoryBase.php';

class NiddkcrRepository extends RepositoryBase {

    public $show_name = 'NIDDKCR';
    public $whole_name = 'NIDDK&nbsp;Central&nbsp;Repository';
    public $id = '0018';
    public $source = "https://www.niddkrepository.org/";
    public $search_fields = ['dataset.ID','dataset.title','disease.name','treatment.title'];
    public $facets_fields = ['organism.scientificName'];
    public $facets_show_name = [
        'organism.scientificName'=>'Organism',

    ];
    public $index = 'niddkcr';
    public $type = 'dataset';
    //search page
    public $datasource_headers = ['dataset.title', 'dataset.ID', 'disease.name','organism.scientificName','treatment.title' ];
    public $core_fields_show_name = [
        'dataset.title'=>'Title',
        'dataset.ID'=>'ID',
        'organism.scientificName'=>'Organism',
        'treatment.title'=>'Treatment',
        'disease.name'=>'Disease'

    ];

    //search-repository page
    public $headers = ['Title', 'ID','Disease','Organism','Treatment' ];
    public $header_ids = ['dataset.title', 'dataset.ID','disease.name', 'organism.scientificName','treatment.title'];

    //display-item page
    public $core_fields = ['dataset.title', 'dataset.ID', 'dataset.downloadURL',
        'organization.name','organization.homePage',"organization.ID",'organization.abbreviation',
        'dataRepository.abbreviation', 'dataRepository.name','dataRepository.ID','dataRepository.homePage',
        'disease.name','treatment.title'
    ];


    public $link_field = 'dataset.title';
    public $source_main_page = "https://www.niddkrepository.org/";
    public $sort_field = 'dataset.dateReleased';

    public function show_table($results, $query, $filters) {

        $show_array = [];
        $ids = $this->header_ids;

        $filtersText = "";
        foreach ($filters as $facetItem => $filterItems) {
            $filtersText .= $facetItem . '@';
            $filtersText .= implode(',', $filterItems);
            $filtersText .= '$';
        }
        if (isset($filters)) {
            $filtersText = '&filters=' . substr($filtersText, 0, strlen($filtersText) - 1);
        }

        for ($i = 0; $i < count($results); $i++) {
            $show_line = [];
            $r = $results[$i];
            foreach ($ids as $id) {
                $id_list = explode('.', $id);
                $idLevel = count($id_list);
                $id0 = $id_list[0];
                $id1 = $id_list[1];
                $show = '';
                if ($idLevel == 3) {
                    $id2 = $id_list[2];

                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1][$id2] = implode(' ', $r['highlight'][$id]);
                    }
                    if (isset($r['_source'][$id0][$id1][0][$id2])) {
                        $show = parent::shorten($r['_source'][$id0][$id1][0][$id2]);
                        if ($r['_source'][$id0][$id1][0][$id2] == '' || $r['_source'][$id0][$id1][0][$id2] == ' ') {
                            $show = '';
                        }
                    }
                } else {
                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                    }
                    if($id=='disease.name'){
                        foreach($r['_source']['disease'] as $disease){
                            $show=$show.$disease['name'].'<br>';
                        }

                    }
                    if($id=='organism.scientificName'){
                        foreach($r['_source']['organism'] as $disease){
                            $show=$show.$disease['scientificName'].'<br>';
                        }

                    }
                    if (isset($r['_source'][$id0][$id1])) {
                        $show = parent::shorten($r['_source'][$id0][$id1]);
                        if ($id == 'dataset.title') {
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataset']['title'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = '';
                        }
                    }
                }

                if ($id == 'dataset.title' || $id == 'dataset.description') {
                    $show = '<div user="comment">' . $show . '</div>';
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }
}

?>