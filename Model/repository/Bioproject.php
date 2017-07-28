<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class BioProjectRepository extends RepositoryBase
{

    public $repoShowName = 'BioProject';
    public $wholeName = '';
    public $id = '0008';
    public $index = 'bioproject';
    public $type = 'dataset';

    public $source = "http://www.ncbi.nlm.nih.gov/bioproject/?term=";
 //   public $searchFields = ["dataset.ID", 'dataset.title', 'dataset.keywords', 'dataset.description', 'taxonomicinformation.name.raw','taxonomicinformation.ncbiID','publication.ID','organism.strain'];

    public $facetsFields = ['taxonomicinformation.name.raw', 'dataset.keywords.raw'];
    public $facetsShowName = [
        'taxonomicinformation.name.raw' => 'Organsim',
        'dataset.keywords.raw' => 'Keywords'];


    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.keywords', 'access.accesstypes'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'access.accesstypes' => 'Access Type',
        'dataset.keywords'=>'Keywords'
    ];

    public $searchRepoHeader = ['Title', 'ID', 'Keywords',  'Date Released','Access Type'];//,'Citation count'
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.keywords', 'datasetDistribution.dateReleased','access.accesstypes'];//'citation.count'

    public $sort_field = 'datasetDistribution.dateReleased';
    public $description = 'A BioProject is a collection of biological data related to a single initiative, originating from a single organization or from a consortium. A BioProject record provides users a single place to find links to the diverse data types generated for that project.';
    public function show_table($results, $query)
    {
        $show_array = [];
        $ids = $this->searchRepoField;

        for ($i = 0; $i < count($results); $i++) {
            $show_line = [];
            $r = $results[$i];

            foreach ($ids as $id) {
                $id_list = explode('.', $id);
                $idLevel = count($id_list);
                $id0 = $id_list[0];
                $id1 = $id_list[1];
                $show = '';

                if (isset($r['highlight'][$id])) {
                    $r['_source'][$id0][$id1] = implode(' ', $r['highlight'][$id]);

                }
                if (isset($r['_source'][$id0][$id1])) {

                    $show = $r['_source'][$id0][$id1];
                    if ($id == 'dataset.title') {
                        $r['_source']['dataset']['title'] = reduce_duplicate_in_title($r['_source']['dataset']['title']);
                        $show = '<a class="hyperlink" user ="result-heading" href="display-item.php?repository=' . $this->id . '&id=' . $r['_id'] . '&query=' . $query . '">' . $r['_source']['dataset']['title'] . '</a>';
                    }
                    if($id1==='dateReleased'){

                        $show = format_time($show);
                    }
                    if ($r['_source'][$id0][$id1] == '' || $r['_source'][$id0][$id1] == ' ') {
                        $show = '';
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

    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);
        $search_results['title'][1]=reduce_duplicate_in_title($search_results['title'][1]);
        return $search_results;
    }
}

?>
