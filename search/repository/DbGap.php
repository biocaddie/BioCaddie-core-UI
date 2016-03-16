<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

/* The database of Genotypes and Phenotypes (dbGaP) was developed to archive 
 * and distribute the results of studies that have investigated the 
 * interaction of genotype and phenotype. */
class DbGapRepository extends RepositoryBase {

    public $show_name = 'dbGaP';
    public $whole_name = 'The&nbsp;database&nbsp;of&nbsp;Genotypes&nbsp;and&nbsp;Phenotypes ';
    public $id = '0001';
    public $source = "http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/study.cgi?study_id=";
    public $search_fields = ['title', 'category', 'disease', 'MESHterm'];
    public $facets_fields = ['ConsentType', 'IRB'];
    public $facets_show_name = ['ConsentType' => 'Consent Type', 'IRB' => 'IRB'];
    public $index = 'phenodisco';
    public $type = 'dbgap';
    public $headers = ['Title', 'Components', 'Cohort', 'Platform', 'IRB', 'Consent', 'Disease'];
    public $header_ids = ['title', 'phenID', 'cohort', 'platform', 'IRB', 'ConsentType', 'category'];
    public $datasource_headers = ['title', 'category', 'Type', 'ConsentType'];
    public $core_fields = ['path', 'title', 'category', 'Type', 'ConsentType',
        'AgeMax', 'AgeMin', 'Demographics', 'FemaleNum',
        'MaleNum','OtherGenderNum','UnknownGenderNum', 'IDName', 'MESHterm', 'age',
        'phenDesc', 'phenType', 'topic','IRB','attributes','cohort','desc','disease','gender','geography',
        'history','inexclude','measurement','phen','phenCUI','phenID','phenMap','phenName','platform'];
    public $core_fields_show_name = ['path' => 'Path',
        'title' => 'Title',
        'category' => 'Category',
        'Type' => 'Type',
        'ConsentType' => 'Consent type',
        'AgeMax' => 'Age max',
        'AgeMin' => 'Age min',
        'Demographics' => 'Demographics',
        'FemaleNum' => 'Female number',
        'MaleNum' => 'Male number',
        'IDName' => 'ID name',
        'MESHterm' => 'Mesh term',
        'age' => 'Age',
        'phenDesc' => 'Pheno description',
        'phenType' => 'Pheno type',
        'topic' => 'Topic',
        'IRB' => 'IRB',
        'OtherGenderNum'=>'Other gender number',
        'UnknownGenderNum'=>'Unknown gender number',
        'attributes'=>'Attributes',
        'cohort'=>'Cohort',
        'desc'=>'Description',
        'disease'=>'Disease',
        'gender'=>'Gender',
        'geography'=>'Geography',
        'history'=>'History',
        'inexclude'=>'include/exclude',
        'measurement'=>'Measurement',
        'phen'=>'phenotype',
        'phenCUI'=>'phenCUI',
        'phenID'=>'phenID',
        'phenMap'=>'phenMap',
        'phenName'=>'phenName',
        'platform'=>'platform'
        ];
    public $link_field = 'title';
    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/gap';
    public $sort_field = 'cohort';

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
                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id] = implode(' ', $r['highlight'][$id]);
                }
                if (isset($r['_source'][$id])) {
                    if ($id == 'title') {
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=path&id=' . $r['_id'] .'&query=' . $query . $filtersText . '">' . $r['_source'][$id] . '</a>';
                    } elseif ($id == 'phenID') {
                        //$show = 'study has ' . count(explode(' ', $r['_source'][$id])) . ' variable components';
                        $show = count(explode(' ', $r['_source'][$id]));
                    } else {
                        if ($r['_source'][$id] == '' || $r['_source'][$id] == ' ') {
                            $show = 'n/a';
                        } else {
                            $show = '';
                            foreach (explode(';', $r['_source'][$id]) as $single) {
                                $show = $show . $single . '<br>';
                            }
                        }
                    }
                }
                if ($id == 'platform') {
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