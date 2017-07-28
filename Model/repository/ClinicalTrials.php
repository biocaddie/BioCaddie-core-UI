<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/26/16
 * Time: 10:21 AM
 */

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class ClinicalTrialsRepository extends RepositoryBase {

    public $repoShowName = 'ClinicalTrials.gov';
    public $wholeName = '';
    public $id = '0009';
    public $source = "https://clinicaltrials.gov/ct2/show/";
    //public $searchFields = ['dataset.ID','dataset.available','dataset.creator','dataset.description','dataset.keywords','dataset.title',
    //                        'disease.name','study.selectioncriteria','study.ID','study.types','study.studygroups',
    //                        'treatment.name','treatmemnt.description','treatment.agent','publication.title','grant.funders'];
    public $facetsFields = ['treatment.name.raw','disease.name.raw'];
    public $facetsShowName = [
        'treatment.name.raw'=>'Treatment',
        'disease.name.raw'=>'Disease'
    ];
    public $index = 'clinicaltrials';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.description','dataset.creator' ];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.creator' => 'Creator',
        'dataset.ID' => 'ID',
        'dataset.description'=>'Description'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Disease'];
    public $searchRepofield = ['dataset.title','dataset.ID' , 'disease.name'];


    public $source_main_page = 'https://clinicaltrials.gov/';
    public $sort_field = '';
    public $description='ClinicalTrials.gov is a registry and results database of publicly and privately supported clinical studies of human participants conducted around the world.';
    public function show_table($results, $query) {

        $show_array = [];
        $ids = $this->searchRepofield;

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
                        if ($id == 'dataset.title') {
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&id=' . $r['_id'] . '&query=' . $query  . '">' . $r['_source']['dataset']['title'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = 'n/a';
                        }
                    }
                }

                if ($id == 'dataset.title' || $id == 'dataset.description') {
                    $show = '<div user="comment">' . $show . '</div>';
                }

                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }

   /* public function getDisplayItemView($rows)
    {
        $search_results = [];
        $logo_link_icon = '<img style="height: 20px " src="./img/repositories/'. $this->id.'.png">';
        $search_results['logo']=$logo_link_icon;
        $search_results['repo_id']=$this->id;
        $search_results['show_order']=['dataset','datasetdistribution','access','disease','grant','study','treatment','publication','dataRepository'];
        foreach(array_keys($rows) as $key){
            if($key=='dataItem'){
                continue;
            }
            $search_results[$key]=[];
            foreach(array_keys($rows[$key]) as $subkey){

                if(sizeof($rows[$key][$subkey])==0){
                    continue;
                }
                if(in_array($subkey,['oversight_info','recruits'])){
                    $display_value = is_array($rows[$key][$subkey]) ? convert_array_to_string_two_level($rows[$key][$subkey]) : $rows[$key][$subkey];
                }
                else{
                    $display_value = is_array($rows[$key][$subkey]) ? convert_array_to_string_one_level($rows[$key][$subkey]) : $rows[$key][$subkey];
                }

                $a = check_url($display_value);
                if($key=='dataset' && $subkey=='title') {
                    $search_results['title']=[$subkey, $display_value, $rows['access']['landingPage']];
                }
                elseif($a){
                    array_push($search_results[$key],[$subkey,$display_value,$display_value]);
                }
                else{
                    array_push($search_results[$key],[$subkey,$display_value]);
                }

            }
        }

        return $search_results;
    }
*/


}

?>