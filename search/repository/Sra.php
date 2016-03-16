<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class SraRepository extends RepositoryBase {

    public $show_name = 'SRA';
    public $whole_name ='Sequence&nbsp;Read&nbsp;Archive';
    public $id = '0007';
    public $source = "http://www.ncbi.nlm.nih.gov/sra/?term=";
    public $search_fields = ['title', 'accession', 'organism'];
    public $facets_fields = ['organism', 'strategy'];
    public $facets_show_name = ['organism' => 'Organsim',
        'strategy' => 'Strategy'];
    public $index = 'sra';
    public $type = 'analysis';
    public $headers = ['Title', 'Accession', 'Organism', 'Strategy', 'Size'];
    public $header_ids = ['title', 'accession', 'organism', 'strategy', 'size'];
    public $datasource_headers = ['title', 'accession', 'organism', 'size',];
    public $core_fields = ['title', 'accession', 'organism', 'strategy', 'size'];
    public $core_fields_show_name = ['title' => 'Title',
        'accession' => 'Accession',
        'organism' => 'Organism',
        'strategy' => 'Strategy',
        'size' => 'Size',
    ];
    public $link_field = 'title';
    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/sra';
    public $sort_field = 'size';

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
                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id] = implode(' ', $r['highlight'][$id]);
                }
                if (isset($r['_source'][$id])) {
                    $show = parent::shorten($r['_source'][$id]);                    
                    if ($id == 'title') {
                        //$show = '<a href="'.$this->source.$r['_source']['link'].'" target="_blank"><u>'.$r['_source']['title'].'</u></a>';
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=link&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source'][$id] . '</a>';
                    }
                    if ($r['_source'][$id] == '' || $r['_source'][$id] == ' ') {
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