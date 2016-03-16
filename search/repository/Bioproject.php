<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class BioProjectRepository extends RepositoryBase {

    public $show_name = 'BioProject';
    public $whole_name = '';
    public $id = '0008';
    public $source = "http://www.ncbi.nlm.nih.gov/bioproject/?term=";
    public $search_fields = ['dataItem.title', 'dataItem.keywords','dataItem.description', 'organism.target.species'];
    public $facets_fields = ['organism.target.species', 'dataItem.keywords'];
    public $facets_show_name = ['organism.target.species' => 'Organsim',
        'dataItem.keywords' => 'Keywords'];
    public $index = 'bioproject';
    public $type = 'dataset';
    public $headers = ['Title', 'ID','Keywords','Organism','Release Date'];//,'Citation count'
    public $header_ids = ['dataItem.title','dataItem.ID', 'dataItem.keywords','organism.target.species','dataItem.releaseDate'];//'citation.count'
    public $datasource_headers = ['dataItem.title', 'dataItem.ID', 'dataItem.releaseDate'];
    public $core_fields = ['dataItem.title', 'dataItem.ID', 'organism.target.species', 'dataItem.releaseDate','dataItem.keywords','dataItem.description','organism.target.ncbiID'];
    public $core_fields_show_name = ['dataItem.title' => 'Title',
        'dataItem.ID' => 'ID',
        'organism.target.species' => 'Organism',
        'dataItem.releaseDate' => 'Release Date',
        'dataItem.keywords'=>'Keywords',
        'dataItem.description'=>"Description",
        'organism.target.ncbiID'=>"NCBI ID"
    ];
    public $link_field = 'dataItem.title';
    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/bioproject';
    public $sort_field = 'citation.count';

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
                //echo '<br>';
                $id_list = explode('.', $id);
                $idLevel = count($id_list);
                $id0 = $id_list[0];
                $id1 = $id_list[1];
                $show = '';
                if ($idLevel == 3) {
                    $id2 = $id_list[2];

                    if (isset($r['highlight'][$id])) {
                      //  if(!is_array($r['highlight'][$id])) {
                            $r['_source'][$id0][$id1][$id2] = implode(' ', $r['highlight'][$id]);
                      //  }
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
                       //echo $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);
                    if (isset($r['_source'][$id0][$id1])) {

                        if($id=='dataItem.releaseDate'){
                            $releasetime = $r['_source']['dataItem']['releaseDate'];
                            $r['_source']['dataItem']['releaseDate']=date("m-d-Y",strtotime($releasetime));
                        }
                        $show = $r['_source'][$id0][$id1];
                        if ($id == 'dataItem.title') {
                            $r['_source']['dataItem']['title']=reduce_dupilicate_in_title($r['_source']['dataItem']['title']);
                            $show = '<a class="hyperlink" user ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataItem.ID&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['dataItem']['title'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = '';
                        }
                    }
                }

                if ($id == 'dataItem.title' || $id == 'dataItem.description') {
                    $show = '<div user="comment">' . $show . '</div>';
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
       // print_r($show_array);
        return $show_array;
    }
}
function reduce_dupilicate_in_title($text)
{

    if (strpos($text, ':') !== false) {
        $first = trim(substr($text, 0, strlen($text) / 2));
        $second = trim(substr($text, strlen($text) / 2 + 1));
        if (strcmp($first, $second) == 0) {
            return $first;
        }
    }
        return $text;

}
?>
