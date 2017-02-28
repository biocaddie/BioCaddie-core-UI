<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class CtnRepository extends RepositoryBase {

    public $show_name = 'CTN';
    public $whole_name ='Clinical&nbsp;Trials&nbsp;Network';
    public $id = '0015';
    public $source = "";

    public $search_fields = ['dataset.ID', 'dataset.title', 'dataset.description','dataset.keywords','organism.name','organism.scientificName','dataset.creator'];
    public $facets_fields = ['dataset.keywords'];
    public $facets_show_name = [
        'dataset.keywords' => 'Keywords',
    ];
    public $index = 'ctn';
    public $type = 'dataset';

    //search page
    public $datasource_headers = ['dataset.title', 'dataset.keywords','dataset.description'];
    public $core_fields_show_name = [
        'dataset.title' => 'Title',
        'dataset.keywords' => 'Keywords',
        'dataset.description'=>'Description',
    ];


    //search-repository page
    public $headers = ['Title', 'Date Issued','Organization','Organism','Description'];
    public $header_ids = [ 'dataset.title', 'dataset.keywords','organization.name','dataset.description'];

    //display-item page
    public $core_fields = [
        'release_date',
        'dataset.title',
        'dataset.creator',
        'dataset.description',
        'dataset.keywords',
        'dataset.ID',
        'dataset.downloadURL',
        'organism.name',
        'organism.scientificName',
        'url'
    ];



    public $link_field = 'dataset.title';
    public $source_main_page = 'http://ctndisseminationlibrary.org/';
    public $sort_field = 'release_date';

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
                //echo $id;
                $id_list = explode('.', $id);
                $idLevel = count($id_list);
                $id0 = $id_list[0];
                $id1 = $id_list[1];
                $show = 'n/a';
                if ($idLevel == 3) {
                    $id2 = $id_list[2];

                    if (isset($r['highlight'][$id])) {

                        $r['_source'][$id0][$id1][$id2] = implode(' ', $r['highlight'][$id]);

                    }
                    if (isset($r['_source'][$id0][$id1][0][$id2])) {
                        $show = $r['_source'][$id0][$id1][0][$id2];
                        if ($r['_source'][$id0][$id1][0][$id2] == '' || $r['_source'][$id0][$id1][0][$id2] == ' ') {
                            $show = 'n/a';
                        }
                    }
                } else {
                    if (isset($r['highlight'][$id])) {
                        $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);

                    }

                    if (isset($r['_source'][$id0][$id1])) {
                        $show = $r['_source'][$id0][$id1];
                        if ($id == 'dataset.title') {
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataset.title&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataset']['title'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = 'n/a';
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