<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class ArrayExpressRepository extends RepositoryBase {

    public $show_name = 'ArrayExpress';
    public $whole_name = '';
    public $id = '0006';
    public $source = "https://www.ebi.ac.uk/arrayexpress/experiments/";

    public $search_fields = [ 'dataset.ID','dataset.description', 'dataset.title','dataset.dataType','treatment.title'];
    public $facets_fields = ['dataset.dataType','dataset.keywords'];
    public $facets_show_name = [
        'dataset.dataType'=>'Data Type',
        'dataset.keywords'=>'Keywords'
       ]; //
    public $index = 'arrayexpress'; //'geo';
    public $type = 'dataset'; //'array_express';


    //search page
    public $datasource_headers = ['dataset.title', 'dataset.ID', 'dataset.description'];
    public $core_fields_show_name = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'dataset.description'=>'Description',
    ];

    //search-repository page
    public $headers = ['Title', 'ID', 'Description', 'Date Released'];
    public $header_ids = ['dataset.title', 'dataset.ID', 'dataset.description', 'dataset.dateReleased'];

    //display-item page
    public $core_fields = ['citation.count','dataAcquisition.ID','dataAcquisition.title', 'dataRepository.ID','dataRepository.homePage', 'dataRepository.name',
        'dataset.ID','dataset.dataType', 'dataset.description', 'dataset.dateModified','dataset.dateReleased','dataset.dateSubmitted','dataset.downloadURL',
        'dataset.keywords','dataset.provider','dataset.title','organism.name','organization.ID','organization.abbreviation','organization.homePage','organization.name','treatment.title'
        ];

    public $link_field = 'dataset.title';
    public $source_main_page = 'https://www.ebi.ac.uk/arrayexpress/';
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
                $show = 'n/a';
                $id_list = explode('.', $id);
                $id0 = $id_list[0];
                $id1 = $id_list[1];

                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                }

                if (isset($r['_source'][$id0][$id1])) {
                    $show = parent::shorten($r['_source'][$id0][$id1]);
                    if ($id == 'dataset.title') {
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataset.ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataset']['title'] . '</a>';
                    }
                    if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                        $show = 'n/a';
                    }
                }
                if ($id == 'dataset.title' || $id == 'dataset.description' || $id == 'citation.title') {
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