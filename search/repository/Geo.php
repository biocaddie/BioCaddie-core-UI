<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class GeoRepository extends RepositoryBase {

    public $show_name = 'GEO';
    public $whole_name = 'Gene&nbsp;Expression&nbsp;Omnibus';
    public $id = '0003';
    //public $source = "http://www.ncbi.nlm.nih.gov/sites/GDSbrowser?acc=";
    public $source = "http://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=";
    public $search_fields = ['dataItem.title', 'dataItem.description', 'dataItem.source_name', 'dataItem.Type', 'dataItem.series', 'dataItem.ID'];
    public $facets_fields = ["dataItem.organism", "dataItem.platform", 'dataItem.entry_type'];
    public $facets_show_name = ["dataItem.organism" => 'Organsim',
        "dataItem.platform" => 'Platform',
        'dataItem.entry_type' => 'Entry Type'];
    public $index = 'geo';
    public $type = 'dataset';
    public $headers = ['Title', 'Type', 'Assays',
        'Accession', 'Organism', 'Platform', 'Series', 'Citations']; //,'Link','Source name'];
    public $header_ids = ['dataItem.title', 'dataItem.Type', 'dataItem.assays',
        'dataItem.geo_accession', 'dataItem.organism', 'dataItem.platform', 'dataItem.series', 'dataItem.citations']; //,'link','source_name'];
    public $datasource_headers = ['dataItem.title', 'dataItem.geo_accession', 'dataItem.platform', 'dataItem.series'];
    public $core_fields = ['dataItem.title', 'dataItem.geo_accession', 'dataItem.platform', 'dataItem.series', 'dataItem.link',
        'dataItem.platform', 'dataItem.assays', 'dataItem.source_name', 'dataItem.organism', 'dataItem.entry_type', 'dataItem.description', 'dataItem.Type', 'dataItem.ID'];
    public $core_fields_show_name = ['dataItem.title' => 'Title',
        'dataItem.geo_accession' => 'Geo accession',
        'dataItem.platform' => 'Platform',
        'dataItem.series' => 'Series',
        'dataItem.link' => 'Link',
        'dataItem.platform' => 'Platform',
        'dataItem.assays' => 'Assays',
        'dataItem.source_name' => 'Source name',
        'dataItem.organism' => 'Organism',
        'dataItem.entry_type' => 'Entry type',
        'dataItem.description' => 'Description',
        'dataItem.Type' => 'Type',
        'dataItem.ID' => 'ID'];
    public $link_field = 'dataItem.title'; //'geo_accession';
    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/geo/';
    public $sort_field = 'dataItem.assays';

    /*public function show_table($results, $query, $filters) {
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
                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id] = implode(' ', $r['highlight'][$id]);
                }
                if (isset($r['_source'][$id])) {
                    $show = $r['_source'][$id];

                    if ($id == 'title') {
                        //$show = '<a href="'.$this->source.$r['_source']['geo_accession'].'" target="_blank"><u>'.$r['_source']['title'].'</u></a>';
                        $show = '<a database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=geo_accession&id=' . $r['_source']['geo_accession'] . '&query=' . $query . $filtersText . '">' . $r['_source'][$id] . '</a>';
                    }
                    if ($r['_source'][$id] == '' || $r['_source'][$id] == ' ') {
                        $show = 'n/a';
                    }
                    if (is_array($show)) {
                        $show = implode('<br>', $show);
                    }
                }
                if ($id == 'description') {
                    $show = '<div database="comment">' . $show . '</div>';
                }
                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }*/
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