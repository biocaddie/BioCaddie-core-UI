<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class NeuromorphoRepository extends RepositoryBase {

    public $show_name = 'NeuroMorpho.Org';
    public $whole_name ='';
    public $id = '0013';
    public $source = "http://neuromorpho.org/neuron_info.jsp?neuron_name=";

    public $search_fields = ['dataset.title', 'dataset.note','organism.name','organism.strain','anatomicalPart.name','treatment.title','cell.name'];
    public $facets_fields = ['studyGroup.name','organism.strain','organism.gender'];
    public $facets_show_name = [
        'studyGroup.name' => 'Study Group',
        'organism.strain'=>'Organism Strain',
        'organism.gender'=>'Gender'
    ];
    public $index = 'neuromorpho';
    public $type = 'dataset';

    //search page
    public $datasource_headers = ['dataset.title', 'organism.strain','dataset.note'];
    public $core_fields_show_name = [
        'dataset.title' => 'Title',
        'organism.strain'=>'Organism Strain',
        'dataset.note'=>'Description',
    ];


    //search-repository page
    public $headers = ['Title', 'Treatment','Organism Strain','Description'];
    public $header_ids = [ 'dataset.title', 'treatment.title', 'organism.strain','dataset.note'];

    //display-item page
    public $core_fields = [
        'anatomicalPart.name',
        'cell.name',
        'dataRepository.ID',
        'dataRepository.homepage',
        'dataRepository.name',
        'dataRepository.abbreviation',
        'dataset.ID',
        'dataset.note',
        'dataset.downloadURL',
        'dataset.title',
        'dimension.name',
        'organism.gender',
        'organism.name',
        'organism.scientificName',
        'organism.strain',
        'organization.ID',
        'organization.name',
        'organization.abbreviation',
        'organization.homepage',
        'studyGroup.name',
        'treatment.title'
    ];



    public $link_field = 'dataset.title';
    public $source_main_page = 'http://neuromorpho.org/';
    public $sort_field = 'dataset.ID';

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
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataset.title&id=' . strip_tags($r['_id']) . '&query=' . $query . $filtersText . '">' . $r['_source']['dataset']['title'] . '</a>';
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