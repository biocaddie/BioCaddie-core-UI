<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

/* The database of Genotypes and Phenotypes (dbGaP) was developed to archive 
 * and distribute the results of studies that have investigated the 
 * interaction of genotype and phenotype. */
class DbGapRepository extends RepositoryBase {

    public $repoShowName = 'dbGaP';
    public $wholeName = 'The&nbsp;database&nbsp;of&nbsp;Genotypes&nbsp;and&nbsp;Phenotypes ';
    public $id = '0001';
    public $source = "http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/study.cgi?study_id=";
    //public $searchFields = ['IDName','title', 'category', 'disease', 'MESHterm','desc','phenID','topic'];
    public $facetsFields = ['ConsentType', 'IRB'];
    public $facetsShowName = ['ConsentType' => 'Consent Type', 'IRB' => 'IRB'];
    public $index = 'phenodisco';
    public $type = 'dbgap';

    public $searchPageField = ['title', 'category', 'Type', 'ConsentType'];
    public $searchPageHeader = ['path' => 'Path',
        'title' => 'Title',
        'category' => 'Category',
        'Type' => 'Type',
        'ConsentType' => 'Consent type',
        ];
    public $searchRepoHeader = ['Title', 'Components', 'Cohort', 'Platform', 'IRB', 'Consent', 'Disease'];
    public $searchRepoField = ['title', 'phenID', 'cohort', 'platform', 'IRB', 'ConsentType', 'category'];


    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/gap';
    public $sort_field = '';
    public $description='The database of Genotypes and Phenotypes (dbGaP) archives and distributes the results of studies that have investigated the interaction of genotype and phenotype.';
    public function show_table($results, $query) {
        $show_array = [];
        $ids = $this->searchRepoField;

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
                        $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&id=' . $r['_id'] .'&query=' . $query . '">' . $r['_source'][$id] . '</a>';
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

    public function getDisplayItemView($rows)
    {

        $search_results = [];
        $logo_link_icon = '&nbsp;&nbsp;&nbsp;<img style="height: 20px ;width:40px" src="./img/repositories/'. $this->id.'.png">';
        $search_results['logo']=$logo_link_icon;
        $search_results['repo_id']=$this->id;
        $search_results['show_order']=['dataset','study','demographics','phenotype'];
        $show_map = ['dataset'=>['IDName','title','path','topic','IRB','measurement','MESHterm','desc','category','ConsentType','attributes'],
            'study'=>['Type','disease','platform','history','cohort','inexclude'],
            'demographics'=>['FemaleNum','MaleNum','UnknownGenderNum','OtherGenderNum','AgeMin','AgeMax','age','gender','geography','Demographics'],
            'phenotype'=>['phen','phenID','phenName','phenCUI','phenType','phenMap','phenDesc']
        ];

        foreach(array_keys($show_map) as $key) {
            $search_results[$key]=[];
            foreach($show_map[$key] as $subkey) {
                $display_value = $rows[$subkey];


                //$a = check_url($display_value);
                if ($subkey == 'title') {
                    $search_results['title'] = [$subkey, $display_value, $this->source . $rows['path']];
                }
                // elseif($a){
                //    array_push($search_results['dataset'],[$key,$display_value,$display_value]);
                // }
                else {
                    array_push($search_results[$key], [$subkey, $display_value]);
                }
            }
        }


        return $search_results;
    }
}

?>