<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class GemmaRepository extends RepositoryBase {

    public $show_name = 'GEMMA';
    public $whole_name = '';
    public $id = '0005';
    public $source = "http://www.chibi.ubc.ca/Gemma/expressionExperiment/showExpressionExperiment.html?id="; //GSE60304
    public $search_fields = ['dataItem.ID','dataItem.dataTypes', 'dataItem.description', 'dataItem.title'];
    public $index = 'gemma';
    public $type = 'dataset';
    //search-repository page
    public $facets_fields = ['organism.source.commonName'];
    public $facets_show_name = ['organism.source.commonName' => 'Organism'];
    public $headers = [ 'Title', 'ID', 'Data Types', 'Description'];
    public $header_ids = ['dataItem.title', 'dataItem.ID', 'dataItem.dataTypes', 'dataItem.description'];
    //display-item page
    public $datasource_headers = ['dataItem.title', 'dataItem.ID', 'dataItem.dataTypes', 'dataItem.description'];
    public $core_fields = ['dataItem.ID', 'dataItem.dataTypes', 'dataItem.description', 'dataItem.title', 'dataResource'];
    public $core_fields_show_name = [
        'dataItem.ID' => 'ID',
        'dataItem.dataTypes' => 'Data Types',
        'dataItem.description' => 'Description',
        'dataItem.title' => 'Title',
        'dataResource' => 'Data Resource',
    ];
    public $link_field = 'dataItem.title';
    public $source_main_page = 'http://www.chibi.ubc.ca/Gemma/home.html';
    public $sort_field = 'dataItem.ID';

    // show table on search-repository page
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
                $id0 = $id_list[0];
                $id1 = $id_list[1];
                $show = 'n/a';
                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                }

                if (isset($r['_source'][$id0][$id1])) {
                    $show = $r['_source'][$id0][$id1];
                    if ($id == 'dataItem.title') {
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataItem.ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataItem']['title'] . '</a>';
                    }
                    if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                        $show = 'n/a';
                    }

                    if (is_array($show)) {
                        $show = implode('<br>', $show);
                    }
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }

        return $show_array;
    }

}

?>