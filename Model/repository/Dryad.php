<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DryadRepository extends RepositoryBase {

    public $repoShowName = 'Dryad';
    public $wholeName ='Dryad&nbsp;Data&nbsp;Repository';
    public $id = '0010';
    public $source = "http://datadryad.org/resource/";

    //public $searchFields = ['dataset.ID', 'dataset.title', 'dataset.keywords.raw', 'dataset.description','dataset.type','datset.creator','identifiers.ID','internal.setID'];
    public $facetsFields = ['dataset.keywords.raw'];
    public $facetsShowName = [
        'dataset.keywords.raw' => 'Keyword',
    ];
    public $index = 'dryad';
    public $type = 'dataset';

    //search page
    public $searchPageField  = ['dataset.title', 'datasetDistribution.dateReleased', 'dataset.description'];
    public $searchPageHeader  = [
        'dataset.title' => 'Title',
        'datasetDistribution.dateReleased' => 'DateIssued',
        'dataset.description'=>'Description',
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Date Issued','Date Released','Description'];
    public $searchRepofield = [ 'dataset.title', 'datasetDistribution.issueDate','datasetDistribution.dateReleased','dataset.description'];

    public $source_main_page = 'http://datadryad.org/';
    public $sort_field = 'datasetDistribution.dateReleased';
    public $description='DataDryad.org is a curated general-purpose repository that makes the data underlying scientific publications discoverable, freely reusable, and citable.';
    public function show_table($results, $query) {

        $show_array = [];
        $ids = $this->searchRepofield;

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
                            $show = '<a class="hyperlink" database ="result-heading" href="display-item.php?repository=' . $this->id . '&idName=dataset.title&id=' . $r['_id'] . '&query=' . $query . '">' . $r['_source']['dataset']['title'] . '</a>';
                        }
                        if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                            $show = 'n/a';
                        }
                    }
                }

                if ($id == 'dataset.title' || $id == 'dataset.description') {
                    $show = '<div class="comment more">' . $show . '</div>';
                }
                if($id=='datasetDistribution.issueDate'||$id=='datasetDistribution.dateReleased'){
                    $show = format_time($show);
                }
                array_push($show_line, $show);
            }
            array_push($show_array, $show_line);
        }
        return $show_array;
    }

    public function getDisplayItemView($rows)
    {
        $landingpage=$rows['identifiers']['ID'][2];
        $search_results = parent::getDisplayItemView($rows);
        $search_results['title'][2]=$landingpage;
        for($i=0;$i<sizeof($search_results['access']);$i++){
            if($search_results['access'][$i][0]==='landingPage'){
            $search_results['access'][$i][1] = $landingpage;
            $search_results['access'][$i][2] = $landingpage;
            }

        }
       // $search_results['identifiers'][0][1]=str_replace($landingpage,'',$search_results['identifiers'][0][1]);
        return $search_results;
    }
}

?>