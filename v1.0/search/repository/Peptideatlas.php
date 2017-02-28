<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/RepositoryBase.php';

class PeptideatlasRepository extends RepositoryBase {

    public $show_name = 'PeptideAtlas';
    public $whole_name = '';
    public $id = '0014';
    public $source = "http://www.peptideatlas.org/";
    public $search_fields = ['treatment.description','dataset.title','dataset.description'];
    public $facets_fields = ['organism.name','instrument.name'];
    public $facets_show_name = [
        'organism.name'=>'Organism',
        'instrument.name'=>'Instrument'

    ];
    public $index = 'peptideatlas';
    public $type = 'dataset';
    //search page
    public $datasource_headers = ['dataset.title', 'dataset.description', 'pmid','instrument.name'];
    public $core_fields_show_name = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'pmid'=>'Pmid',
        'instrument.name'=>'Instrument'

    ];

    //search-repository page
    public $headers = ['Title', 'Description', 'Pmid','Organism','Instrument' ];
    public $header_ids = ['dataset.title', 'dataset.description', 'pmid','organism.name','instrument.name' ];

    //display-item page
    public $core_fields = ['dataset.title', 'dataset.description', 'treatment.description','dataset.id',
        'dataset.downloadURL','pmid',
        'organization.name','organization.homePage',
        "organization.ID","organization.abbreviation",'organism.strain','organism.name',
        'dataRepository.name','dataRepository.ID','dataRepository.homePage','instrument.name'

    ];


    public $link_field = 'dataset.title';
    public $source_main_page = "http://www.peptideatlas.org/";
    public $sort_field = 'pmid';

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

                if($idLevel == 1){
                    if($id=='pmid'){
                        $show = substr($r['_source'][$id0],5);
                    }
                    else {
                        $show = $r['_source'][$id0];
                    }
                }
                elseif ($idLevel == 3) {
                    $id2 = $id_list[2];

                    if (isset($r['highlight'][$id])) {

                        $r['_source'][$id0][$id1][$id2] = implode(' ', $r['highlight'][$id]);

                    }
                    if (isset($r['_source'][$id0][$id1][0][$id2])) {
                        $show = $r['_source'][$id0][$id1][0][$id2];
                        if ($r['_source'][$id0][$id1][0][$id2] == '' || $r['_source'][$id0][$id1][0][$id2] == ' ') {
                            $show = '';
                        }
                    }
                } else {
                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                    }
                    if($id=='organism.name') {
                        $show = parent::shorten($r['_source'][$id0][0][$id1]);                        
                    }
                    if (isset($r['_source'][$id0][$id1])) {
                        $show = $r['_source'][$id0][$id1];
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