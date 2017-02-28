<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/RepositoryBase.php';

class ProteomexchangeRepository extends RepositoryBase {

    public $show_name = 'Proteomexchange';
    public $whole_name = 'Proteomexchange';
    public $id = '0022';
    public $source = "http://www.proteomexchange.org/";
    public $search_fields = ['dataset.title','dataset.ID','keywords'];
    public $facets_fields = ['keywords','organism.name'];
    public $facets_show_name = [
        'keywords'=>'Keywords',
        'organism.name'=>'Organism'

    ];
    public $index = 'proteomexchange';
    public $type = 'dataset';
    //search page
    public $datasource_headers = ['dataset.title', 'dataset.ID', 'instrument.name','dataset.dateReleased' ];
    public $core_fields_show_name = [
        'dataset.title'=>'Title',
        'dataset.ID'=>'ID',
        'instrument.name'=>'Instrument',
        'dataset.dateReleased'=>'Date Released'

    ];

    //search-repository page
    public $headers = ['Title', 'ID', 'Keywords','Publication','Organism'];
    public $header_ids = ['dataset.title', 'dataset.ID','keywords','publication.name','organism.name' ];

    //display-item page
    public $core_fields = ['dataset.title', 'dataset.ID', 'dataset.downloadURL','dataset.storeIn','dataset.dateReleased',
        'organization.name','organization.homePage',"organization.ID",'organization.abbreviation',
        'dataRepository.abbreviation', 'dataRepository.name','dataRepository.ID','dataRepository.homePage',
        'keywords','organism.name','instrument.name','publication.name','person.name'

    ];


    public $link_field = 'dataset.title';
    public $source_main_page ="http://www.proteomexchange.org/";
    public $sort_field = '';

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
                        $show = $r['_source'][$id0][$id1][0][$id2];
                        if ($r['_source'][$id0][$id1][0][$id2] == '' || $r['_source'][$id0][$id1][0][$id2] == ' ') {
                            $show = '';
                        }
                    }
                } if($id=='keywords'){
                        foreach($r['_source']['keywords'] as $organism){
                            $show=$show.$organism.'<br>';
                        }
                    }

                else {

                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                    }

                    if($id=='person.name'){
                        foreach($r['_source']['person'] as $person){
                            $show=$show.$person['name'].'<br>';
                        }

                    }
                    if($id=='person.name'){
                        foreach($r['_source']['person'] as $person){
                            $show=$show.$person['name'].'<br>';
                        }

                    }
                    if($id=='organism.name'){
                        foreach($r['_source']['organism'] as $organism){
                            $show=$show.$organism['name'].'<br>';
                        }
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