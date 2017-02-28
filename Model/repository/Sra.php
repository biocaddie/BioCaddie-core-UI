<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class SraRepository extends RepositoryBase {

    public $repoShowName = 'SRA';
    public $wholeName ='Sequence&nbsp;Read&nbsp;Archive';
    public $id = '0007';
    public $source = "http://www.ncbi.nlm.nih.gov/sra/?term=";
   // public $searchFields = ['title', 'accession', 'organism','strategy'];
    public $facetsFields = ['organism', 'strategy'];
    public $facetsShowName = ['organism' => 'Organsim',
        'strategy' => 'Strategy'];
    public $index = 'sra';
    public $type = 'analysis';

    public $searchRepoHeader = ['Title', 'Accession', 'Organism', 'Strategy', 'Size (Mb)'];
    public $searchRepoField = ['title', 'accession', 'organism', 'strategy', 'size'];

    public $searchPageField = ['title', 'accession', 'organism', 'size',];
    public $searchPageHeader = ['title' => 'Title',
        'accession' => 'Accession',
        'organism' => 'Organism',
        'strategy' => 'Strategy',
        'size' => 'Size',
    ];

    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/sra';
    public $sort_field = '';
    public $description='The Sequence Read Archive (SRA) stores raw sequencing data from the next generation of sequencing platforms including Roche 454 GS System®, Illumina Genome Analyzer®, Applied Biosystems SOLiD® System, Helicos Heliscope®, Complete Genomics®, and Pacific Biosciences SMRT®.';
    public function show_table($results, $query) {
        $show_array = [];
        $ids = $this->searchRepoField;

        for ($i = 0; $i < count($results); $i++) {
            $show_line = [];
            $r = $results[$i];
            foreach ($ids as $id) {
                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id] = implode(' ', $r['highlight'][$id]);
                }
                if (isset($r['_source'][$id])) {
                    $show = shorten($r['_source'][$id]);
                    if ($id == 'title') {
                        //$show = '<a href="'.$this->source.$r['_source']['link'].'" target="_blank"><u>'.$r['_source']['title'].'</u></a>';
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&id=' . $r['_id'] . '&query=' . $query . '">' . $r['_source'][$id] . '</a>';
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
    public function getDisplayItemView($rows)
    {
        $search_results = [];
        $logo_link_icon = '&nbsp;&nbsp;&nbsp;<img style="height: 30px ;width:60px" src="./img/repositories/'. $this->id.'.png">';
        $search_results['logo']=$logo_link_icon;
        $search_results['repo_id']=$this->id;
        $search_results['show_order']=['dataset'];
        $search_results['dataset']=[];
        foreach(array_keys($rows) as $key) {
            $display_value = $rows[$key];
            $a = check_url($display_value);
            if($key=='title') {
                $search_results['title']=[$key, $display_value, $rows['link']];
            }
            elseif($a){
                array_push($search_results['dataset'],[$key,$display_value,$display_value]);
            }
            else{
                array_push($search_results['dataset'],[$key,$display_value]);
            }
        }


        return $search_results;
    }


}

?>