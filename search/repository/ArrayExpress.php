<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class ArrayExpressRepository extends RepositoryBase {

    public $show_name = 'ArrayExpress';
    public $whole_name = '';
    public $id = '0006';
    public $source = "https://www.ebi.ac.uk/arrayexpress/experiments/browse.html?query=";
    public $search_fields = ['dataItem.dataTypes', 'dataItem.description', 'dataItem.experimentType', 'dataItem.title'];
    public $facets_fields = ['dataItem.experimentType','organism.experiment.species'];//'dataItem.dataTypes',
    public $facets_show_name = [
        //'dataItem.dataTypes' => 'Data Types',
        'dataItem.experimentType'=>'Experiment Type',
        'organism.experiment.species'=>'Organism'
       ]; //
    public $index = 'arrayexpress'; //'geo';
    public $type = 'dataset'; //'array_express';
    //search-repository page
    public $headers = ['Title', 'ID', 'Description', 'Experiment Type', 'Release Date'];
    public $header_ids = ['dataItem.title', 'dataItem.ID', 'dataItem.description', 'dataItem.experimentType', 'dataItem.releaseDate'];
    //display-item page
    public $datasource_headers = ['dataItem.title', 'dataItem.ID', 'dataItem.description'];
    public $core_fields = ['citation.count', 'dataItem.ID', 'dataItem.dataTypes', 'dataItem.description', 'dataItem.experimentType', 'dataItem.lastUpdateDate',
        'dataItem.releaseDate', 'dataItem.submissionDate', 'dataItem.title', 'dataResource', 'organism.experiment.species']; //['title', 'accession', 'organism', 'Type', 'link','assays','released'];
    public $core_fields_show_name = ['citation.count' => 'Citation Count',
        'dataItem.ID' => 'ID',
        'dataItem.dataTypes' => 'Data Types',
        'dataItem.description' => 'Description',
        'dataItem.experimentType' => 'Experiment Type',
        'dataItem.lastUpdateDate' => 'Last Update Date',
        'dataItem.releaseDate' => 'Release Date',
        'dataItem.submissionDate' => 'Submission Date',
        'dataItem.title' => 'title',
        'dataResource' => 'Data Resource',
        'organism.experiment.species' => 'Experiment Species'];
    public $link_field = 'dataItem.title';
    public $source_main_page = 'https://www.ebi.ac.uk/arrayexpress/';
    public $sort_field = 'dataItem.experimentType';

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
                $show = 'n/a';
                $id_list = explode('.', $id);
                $id0 = $id_list[0];
                $id1 = $id_list[1];

                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                }

                if (isset($r['_source'][$id0][$id1])) {
                    $show = parent::shorten($r['_source'][$id0][$id1]);
                    if ($id == 'dataItem.title') {
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataItem.ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataItem']['title'] . '</a>';
                    }
                    if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                        $show = 'n/a';
                    }
                }
                if ($id == 'dataItem.title' || $id == 'dataItem.description' || $id == 'citation.title') {
                    $show = '<div database="comment">' . $show . '</div>';
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }

}

?>