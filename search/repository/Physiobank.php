<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/RepositoryBase.php';

class PhysiobankRepository extends RepositoryBase {

    public $show_name = 'PhysioBank';
    public $whole_name = '';
    public $id = '0021';
    public $source = "http://physionet.org/physiobank/";
    public $search_fields = ['dataset.description','dataset.title','publication.name'];
    public $facets_fields = ['dataset.dataType'];
    public $facets_show_name = [
        'dataset.dataType'=>'Data Type',

    ];
    public $index = 'physiobank';
    public $type = 'dataset';
    //search page
    public $datasource_headers = ['dataset.title', 'dataset.description', 'dataset.dataType' ];
    public $core_fields_show_name = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'dataset.dataType'=>'Data Type',

    ];

    //search-repository page
    public $headers = ['Title', 'Description', 'Datatype','Publication'];
    public $header_ids = ['dataset.title', 'dataset.description','dataset.dataType','publication.name'];

    //display-item page
    public $core_fields = ['dataset.title', 'dataset.description', 'dataset.downloadURL','dataset.dataType','publication.name',
        'organization.name','organization.homePage',"organization.ID",'organization.abbreviation',
        'dataRepository.abbreviation', 'dataRepository.name','dataRepository.ID','dataRepository.homePage',

    ];


    public $link_field = 'dataset.title';
    public $source_main_page = "http://physionet.org/physiobank/";
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