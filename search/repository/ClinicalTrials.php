<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/26/16
 * Time: 10:21 AM
 */

require_once dirname(__FILE__) . '/RepositoryBase.php';

class ClinicalTrialsRepository extends RepositoryBase {

    public $show_name = 'ClinicalTrials';
    public $whole_name = '';
    public $id = '0009';
    public $source = "https://clinicaltrials.gov/ct2/show/";
    public $search_fields = ['Dataset.description','Dataset.briefTitle','Dataset.keyword', 'Disease.name','Study.recruits.criteria'];
    public $facets_fields = ['StudyGroup.type','Study.studyType','Study.status'];
    public $facets_show_name = [
        'Study.phase'=>'Study Phase',
        'StudyGroup.type'=>'Study Group Type',
        'Study.studyType'=>'Study Type',
        'Study.status'=>'Study Status'
    ];
    public $index = 'clinicaltrials';
    public $type = 'dataset';
    //search page
    public $datasource_headers = ['Dataset.briefTitle', 'Dataset.creator', 'Study.phase','Dataset.description' ];

    //search-repository page
    public $headers = ['Title', 'Creator', 'Study Phase','Disease','Status','Deposition Date','Country' ];
    public $header_ids = ['Dataset.briefTitle', 'Dataset.creator', 'Study.phase','Disease.name','Study.status','Dataset.depositionDate','Study.location.country' ];

    //display-item page
    public $core_fields = ['Dataset.briefTitle',
        'Dataset.creator',
        'Dataset.description',
        'Dataset.depositionDate',
        'Dataset.verificationDate',
        'Dataset.title',
        'Dataset.has_expanded_access',
        'Dataset.keyword',
        'Dataset.releaseDate',
        'Dataset.is_fda_regulated',
        'DataSet.identifier',
        'clinical_study.oversight_info',
        'clinical_study.oversight_info.authority',
        'clinical_study.oversight_info.has_dmc',
        'Treatment.title',
        'Treatment.description',
        'Treatment.agent',
        'Grant.funder',
        'Disease.name',
        'Study.identifier',
        'Study.location',
        'Study.location.city',
        'Study.location.name',
        'Study.location.othercountries',
        'Study.location.country',
        'Study.recruits',
        'Study.recruits.maximum_age',
        'Study.recruits.gender',
        'Study.recruits.criteria',
        'Study.recruits.minimum_age',
        'Study.phase',
        'Study.studyType',
        'Study.status',
        'Study.homepage'
        ];

    public $core_fields_show_name = [
        'Dataset.briefTitle' => 'Title',
        'Dataset.creator' => 'Creator',
        'Study.phase' => 'Study Phase',
        'Disease.name' => 'Disease',
        'Study.status' => 'Study Status',
        'Dataset.depositionDate' => 'Deposition Date',
        'Dataset.description'=>'Description',
        ];


    public $link_field = 'Dataset.briefTitle';
    public $source_main_page = 'https://clinicaltrials.gov/';
    public $sort_field = 'Dataset.ReleaseDate';

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
                        if ($id == 'Dataset.briefTitle') {
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=Dataset.briefTitle&id=' . $r['_id'] . '&query=' . $query . $filtersText . '">' . $r['_source']['Dataset']['briefTitle'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = 'n/a';
                        }
                    }
                }

                if ($id == 'Dataset.breifTitle' || $id == 'Dataset.description') {
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