<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/RepositoryBase.php';

class openFMRIRepository extends RepositoryBase {

    public $show_name = 'openFMRI';
    public $whole_name = 'Open&nbsp;sharing&nbsp;of&nbsp;Functional&nbsp;Magnetic&nbsp;Resonance&nbsp;Imaging';
    public $id = '0019';
    public $source = "https://openfmri.org/dataset/";
    public $search_fields = ['organism.name','dataset.title','dataset.description'];
    public $facets_fields = ['organism.name'];
    public $facets_show_name = [
        'organism.name'=>'Organism',

    ];
    public $index = 'openfmri';
    public $type = 'dataset';

    //search page
    public $datasource_headers = ['dataset.title', 'dataset.description', 'organism.name' ];
    public $core_fields_show_name = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'organism.name'=>'Organism'
    ];

    //search-repository page
    public $headers = ['Title', 'Description', 'Organism','Organization' ];
    public $header_ids = ['dataset.title', 'dataset.description', 'organism.name','organization.name' ];

    //display-item page
    public $core_fields = ['dataset.title',
        'dataset.description',
        'dataset.downloadURL',
        'dataset.id',
        'organization.name',
        'organization.homePage',
        "organization.ID",
        "organization.abbreviation",
        'dataRepository.abbreviation',
        'dataRepository.name',
        'dataRepository.ID',
        'dataRepository.homePage',
        'organism.name',
        'dataAcquisition.title'
    ];


    public $link_field = 'dataset.title';
    public $source_main_page = "https://openfmri.org";
    public $sort_field = 'dataset.id';

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
                        if($id=='dataset.dateReleased'){
                            $releasetime = $r['_source']['dataset']['dateReleased'];
                            $r['_source']['dataset']['dateReleased']=date("m-d-Y",strtotime($releasetime));
                        }

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
                    $show = '<div user ="comment">' . $show . '</div>';
                }
                if($id=='organism.name'){
                    $show =$r['_source']['organism'][0]['name'];
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }
}

?>