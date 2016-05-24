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
    public $facets_fields = ['cellLine.name.raw','dataset.dataType.raw','biologicalProcess.name.raw',"assay.name.raw"];
    public $facets_show_name = ['cellLine.name.raw' => 'Cell Line',
        'dataset.dataType.raw'=>'Data Type',
        'biologicalProcess.name.raw'=>'Biological Process',
        "assay.name.raw"=>"Assay"
    ];
    public $index = 'lincs';
    public $type = 'dataset';

    //search page
    public $datasource_headers = ['dataset.title', 'assay.name','biologicalProcess.name','dataset.ID'];

    //search-repository page
    public $headers = ['Title', 'ID', 'Bioligical Process',  'Assay','DataType'];//,'Release date'];
    public $header_ids = ['dataset.title', 'dataset.ID', 'biologicalProcess.name', 'assay.name','dataset.dataType'];//, 'dataset.dateReleased'];

    //display-item page
    public $core_fields = ['dataset.title',
        'dataset.dataType', 'dataset.dateReleased',
        'cellLine.name', 'biologicalProcess.name', 'dimension.name',
        'person.name', 'assay.name', 'organization.name',
        'dataset.ID','dataset.downloadURL','internal.projectName','organization.abbreviation','dataset.Modified','protein.name'];
    public $core_fields_show_name = [
        'dataset.title' => 'Title',
        'dataset.dataType' => 'Data Type',
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
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataset']['title'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = 'n/a';
                        }
                    }
                }

                if ($id == 'dataset.title') {
                    $show = '<div user="comment">' . $show . '</div>';
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);

        }
        return $show_array;
    }

}

