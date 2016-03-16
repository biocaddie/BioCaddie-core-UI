<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 12:40 PM
 */
class LincsRepository extends RepositoryBase {

    public $show_name = 'LINCS';
    public $id = '0004';
    public $whole_name = '';
    public $source = "https://lincs.hms.harvard.edu/db/datasets/";
    public $search_fields = ['dataset.title','protein.name', 'cellLine.name', 'biologicalProcess.name','person.name','assay'];
    public $facets_fields = ['cellLine.name','dimension.name','biologicalProcess.name'];
    public $facets_show_name = ['cellLine.name' => 'Cell Line',
        'dimension.name'=>'Dimension',
        'biologicalProcess.name'=>'Biological Process'
    ];
    public $index = 'lincs';
    public $type = 'dataset';

    //search page
    public $datasource_headers = ['dataset.title', 'assay.name','cellLine.name','dataset.ID'];

    //search-repository page
    public $headers = ['Title', 'Cell Line', 'Bioligical Process',  'Assay','DataType'];//,'Release date'];
    public $header_ids = ['dataset.title', 'cellLine.name', 'biologicalProcess.name', 'assay.name','dataset.dataType'];//, 'dataset.dateReleased'];

    //display-item page
    public $core_fields = ['dataset.title',
        'dataset.dataTypes', 'dataset.dateReleased',
        'cellLine.name', 'biologicalProcess.name', 'dimension.name',
        'person.name', 'assay.name', 'organization.name',
        'dataset.ID','dataset.downloadURL','internal.projectName','organization.abbreviation','dataset.Modified','protein.name'];
    public $core_fields_show_name = [
        'dataset.title' => 'Title',
        'dataset.dataTypes' => 'Data Types',
        'dataset.datereleased' => 'Released Date',
        'cellLine.name' => 'Cell Line',
        'biologicalProcess.name' => 'Biological Process',
        'dimension.name' => 'Dimension',
        'person.name' => 'Person Name',
        'assay.name' => 'Assay',
        'organization.name'=>'Organization',
        'dataset.ID'=>'ID',
        'dataset.downloadURL'=>'URL'
    ];
    public $link_field = 'dataset.title';
    public $source_main_page = "http://www.lincsproject.org";
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
                $show = '';
                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id] = implode(' ', $r['highlight'][$id]);
                }

                $idPiece = explode(".", $id);
                if (count($idPiece) == 2) {
                    if (isset($r['_source'][$idPiece[0]][$idPiece[1]])) {
                        $show = $r['_source'][$idPiece[0]][$idPiece[1]]; //$r['_source'][$id];

                        if ($id == 'dataset.title') {//if ($id == 'ID') {
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataset']['title'] . '</a>';
                        }
                        if ($r['_source'][$idPiece[0]][$idPiece[1]] == '' || $r['_source'][$idPiece[0]][$idPiece[1]] == ' ') {
                            $show = '';
                        }
                        if (is_array($show)) {
                            $show = implode('<br>', $show);
                        }
                    }
                } else {
                    if (isset($r['_source'][$idPiece[0]])) {
                        $show = $r['_source'][$idPiece[0]]; //$r['_source'][$id];

                        if ($r['_source'][$idPiece[0]] == '' || $r['_source'][$idPiece[0]] == ' ') {
                            $show = '';
                        }
                        if (is_array($show)) {
                            $show = implode('<br>', $show);
                        }
                    }
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        //print_r($show_array);
        return $show_array;
    }

}

