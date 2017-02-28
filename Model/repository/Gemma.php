<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class GemmaRepository extends RepositoryBase {

    public $repoShowName = 'GEMMA';
    public $wholeName = '';
    public $id = '0005';
    public $source = "http://www.chibi.ubc.ca/Gemma/expressionExperiment/showExpressionExperiment.html?id="; //GSE60304
    //public $searchFields = ['dataset.keywords','dataset.creators', 'dataset.types', 'dataset.title','dataset.ID','dataset.description',
    //                        'identifiers.ID','taxonomicInformation.name','dataRepository.name','organization.name'];
    public $index = 'gemma';
    public $type = 'dataset';

    public $facetsFields = ['taxonomicInformation.name'];
    public $facetsShowName = ['taxonomicInformation.name' => 'Taxonomic Information'];

    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.keywords', 'dataset.description'];
    public $searchPageHeader = [
        'dataset.ID' => 'ID',
        'dataset.keywords' => 'Keywords',
        'dataset.description' => 'Description',
        'dataset.title' => 'Title',
    ];

    //search-repository page
    public $searchRepoHeader = [ 'Title', 'ID', 'Keywords', 'Description'];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.keywords', 'dataset.description'];


    public $source_main_page = 'http://www.chibi.ubc.ca/Gemma/home.html';
    public $sort_field = '';
    public $description='Gemma is a web site, database and a set of tools for the meta-analysis, re-use and sharing of genomics data, currently primarily targeted at the analysis of gene expression profiles. Gemma contains data from thousands of public studies, referencing thousands of published papers. ';
    // show table on search-repository page

    public function getDisplayItemView($rows)
    {
        $search_results = [];
        $logo_link_icon = '<img style="height: 20px ;width:40px" src="./img/repositories/' . $this->id . '.png">';
        $search_results['logo'] = $logo_link_icon;
        $search_results['repo_id'] = $this->id;
        $search_results['show_order'] = ['dataset', 'access', 'identifiers','taxonomicInformation','datasetDistributions','organization','dataRepository'];
        foreach (array_keys($rows) as $key) {

            $newrows = $rows[$key];
            $search_results[$key] = [];
            if($key=='taxonomicInformation'){
                $display_value = is_array($newrows) ? convert_array_to_string($newrows,'name'):$newrows;
                array_push($search_results[$key],['name',$display_value]);
                continue;
            }
            if($key=='datasetDistributions'){
                $result = convert_datasetDist_to_array($rows[$key]);
                foreach($result as $item){
                    array_push($search_results[$key],$item);
                }
                continue;
            }
            if($key=='identifiers'){
                $newrows = $rows[$key][0];
            }
            foreach (array_keys($newrows) as $subkey) {

                if (sizeof($newrows[$subkey]) == 0) {
                    continue;
                }

                $display_value = is_array($newrows[$subkey]) ? convert_array_to_string_one_level($newrows[$subkey]) : $newrows[$subkey];
                if($key=='access'&&$subkey=='landingPage'){
                    $display_value = 'http://'.$rows['access']['landingPage'];
                }

                $a = check_url($display_value);
                if ($key == 'dataset' && $subkey == 'title') {
                    $search_results['title'] = [$subkey, $display_value, 'http://'.$rows['access']['landingPage']];
                }

                elseif ($a && $subkey != 'description') {
                    $link_value = $display_value;
                    array_push($search_results[$key], [$subkey, $display_value, $link_value]);
                } else {
                    array_push($search_results[$key], [$subkey, $display_value]);
                }
            }
        }
        return $search_results;
    }
}

?>