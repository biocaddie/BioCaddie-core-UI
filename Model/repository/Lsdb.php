<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class LsdbRepository extends RepositoryBase {

    public $repoShowName = 'LSDB';
    public $wholeName = "HGVS&nbsp;Locus&nbsp;Specific&nbsp;Mutation&nbsp;Databases";
    public $id = '0066';
    public $source = "http://www.hgvs.org";
    public $facetsFields = ['taxonomicInformation.name.raw', "dataset.types.raw"];
    public $facetsShowName = ['taxonomicInformation.name.raw'=> 'Organism',
        "dataset.types.raw" => 'Data type'];
    public $index = 'lsdb';
    public $type = 'dataset';

    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.description', 'dataset.dateReleased'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.description' => 'Description',
        'dataset.ID'=>'ID',
        'dataset.dateReleased'=>'Date'];


    public $searchRepoHeader = ['Title', 'ID','Types','Description','Date Released'];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.types','dataset.description','dataset.dateReleased'];

    public $source_main_page = "http://www.hgvs.org/dblist/glsdb.htm";
    public $sort_field = 'dataset.dateReleased';
    public $description='Locus Specific Databases (LSDBs) are curated collections of sequence variants in genes associated with disease. LSDBs of cancer-related genes often serve as a critical resource to researchers, diagnostic laboratories, clinicians, and others in the cancer genetics community. LSDBs are poised to play an important role in disseminating clinical classification of variants.';

    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        for($i=0;$i<sizeof($search_results['dataset']);$i++){

            if($search_results['dataset'][$i][0]=="hasPart"){
                unset($search_results['dataset'][$i]);
                break;
            }

        }
        for($i=0;$i<sizeof($search_results['primaryPublication']);$i++){

            if($search_results['primaryPublication'][$i][0]=="authors"){
                $search_results['primaryPublication'][$i][1]=str_replace("<br>",';',$search_results['primaryPublication'][$i][1]);
                break;
            }

        }


        return $search_results;
    }

}

?>