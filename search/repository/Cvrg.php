<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class CvrgRepository extends RepositoryBase {

    public $show_name = 'CVRG';
    public $whole_name ='CardioVascular&nbsp;Research&nbsp;Grid';
    public $id = '0011';
    public $source = "https://eddi.cvrgrid.org/handle/";

    public $search_fields = ['dataset.ID', 'dataset.title', 'dataset.description'];
    public $facets_fields = ['dataset.dataReleased'];
    public $facets_show_name = [
        'dataset.dataReleased' => 'Data Released',
    ];
    public $index = 'cvrg';
    public $type = 'dataset';

    //search page
    public $datasource_headers = ['dataset.title', 'dataset.dataReleased', 'dataset.description'];
    public $core_fields_show_name = [
        'dataset.title' => 'Title',
        'dataset.dataReleased' => 'Date Released',
        'dataset.description'=>'Description',
    ];


    //search-repository page
    public $headers = ['Title', 'Date Issued','Organization','Description'];
    public $header_ids = [ 'dataset.title', 'dataset.dataReleased','organization.name','dataset.description'];

    //display-item page
    public $core_fields = [
        'dataset.ID',
        'dataset.dataReleased',
        'dataset.description',
        'dataset.downloadURL',
        'dataset.title',

        'dataRepository.ID',
        'dataRepository.homepage',
        'dataRepository.name',
        'dataRepository.abbreviation',
        'license.name',
        'organization.ID',
        'organization.name',
        'organization.abbreviation',
        'organization.homepage',
        'person.name',
        'person.affiliation'
    ];



    public $link_field = 'dataset.title';
    public $source_main_page = 'http://cvrgrid.org/';
    public $sort_field = 'dataset.dataReleased';

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