<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/RepositoryBase.php';


class MpdRepository extends RepositoryBase {

    public $show_name = 'MPD';
    public $whole_name = 'Mouse&nbsp;Phenome&nbsp;Database';
    public $id = '0017';
    public $source = "http://phenome.jax.org/";
    public $search_fields = ['dataset.ID','dataset.title','dataset.description','dimension.name'];
    public $facets_fields = ['dataset.gender'];
    public $facets_show_name = [
        'dataset.gender'=>'Gender',

    ];
    public $index = 'mpd';
    public $type = 'dataset';
    //search page
    public $datasource_headers = ['dataset.title', 'dataset.description', 'dataset.gender','dataset.size' ];
    public $core_fields_show_name = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'dataset.gender'=>'Gender',
        'dataset.size'=>'Size'

    ];

    //search-repository page
    public $headers = ['Title', 'Description', 'Gender','Size','Datatype' ];
    public $header_ids = ['dataset.title', 'dataset.description', 'dataset.gender','dataset.size','dataset.dataType'];

    //display-item page
    public $core_fields = ['dataset.title', 'dataset.dataType','dataset.description','dimension.name','dataset.downloadURL','dataset.ID','dataset.size',
        'dataset.gender',
       'organization.name','organization.homePage',
        "organization.ID","organization.abbreviation",
        'dataRepository.abbreviation',
        'dataRepository.name','dataRepository.ID','dataRepository.homePage',
        'organism.name','organism.scientificName','organism.strain'

    ];


    public $link_field = 'dataset.title';
    public $source_main_page = "http://phenome.jax.org/";
    public $sort_field = 'dataset.size';

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
                } else {
                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
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