<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/12/16
 * Time: 9:51 AM
 */
require_once dirname(__FILE__) . '/ElasticSearch.php';

class DuplicateSearch extends ElasticSearch
{

    private $GSEID;
    public $rowsPerPage = 100;
    /*
     * generate query part for GSEID search
     * @return array(string)
     */
    protected function generateQuery(){

        $query_part = [
            'bool'=>[
                'must' => [
                    'multi_match' => [
                        'query' => $this->GSEID,
                        'fields' => "datasetDistributions.accessURL",


                ]
            ]
        ]];

        return $query_part;
    }
    /*
     * generate sort part of search, grant are sort by time
     * @return array(string)
     */
    protected function generate_sort() {
        $sort = ['FY' => ['order' => 'asc', 'missing' => '_last', 'unmapped_type' => 'string']];
        return $sort;
    }

    public function setGSEID($GSEID){
        $GSEID = str_replace("<strong>","",$GSEID);
        $GSEID = str_replace("</strong>","",$GSEID);
        $this->GSEID = $GSEID;
    }
    /*
     * generate search body for ES
     * @return array(string)
     */
    protected function generateBody() {
        $search_query = $this->generateQuery();
        $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
            'size' => $this->rowsPerPage,
            'query' => $search_query,

        ];

        return $body;
    }
    public function __construct(){
        /*parent::__construct($input_array);
        $this->setGSEID($input_array['GSEID']);
        $this->setSearchResult();*/

    }
    public function setSecondaryDatasets($input_array){
        parent::__construct($input_array);
        $this->setGSEID($input_array['GSEID']);
        $this->setSearchResult();
    }
    public function getPrimaryID($source,$id) {
        $input_array = ['esIndex' => $source, 'searchFields' => ['_id'], 'query' => $id];
        $search = new ElasticSearch($input_array);
        $search->setSearchResult();
        $result = $search->getSearchResult();
        $row = $result['hits']['hits'][0]['_source'];
        $searchResults = $row;
        $datasetDistributions = $searchResults['datasetDistributions'];
        $flag= false;
        foreach($datasetDistributions as $datasetDist){
            if(array_key_exists('primary',$datasetDist)){
                $flag=true;
                $ID=explode('=',$datasetDist['accessURL'])[1];
                if($source=='arrayexpress'){
                    $ID=explode('/',$ID)[0];
                }
                return [$flag,$ID];
            }
        }
        return [$flag,null];
    }

    public function getPrimaryTitle($GSEID){
        $input_array = ['esIndex' => 'geo', 'searchFields' => 'datasetDistributions.accessURL', 'query' => $GSEID];
        $search = new ElasticSearch($input_array);
        $search->setSearchResult();
        $result = $search->getSearchResult();
        $row = $result['hits']['hits'][0]['_source'];
        $title = $row['dataset']['title'];
        return $title;
    }
    public function getPrimaryLink($GSEID){
        $input_array = ['esIndex' => 'geo', 'searchFields' => 'datasetDistributions.accessURL', 'query' => $GSEID];
        $search = new ElasticSearch($input_array);
        $search->setSearchResult();
        $result = $search->getSearchResult();
        $result_size = $result['hits']['total'];

        if($result_size>0){
            $id = $result['hits']['hits'][0]['_id'];
            $link='display-item.php?repository=0003&id='.$id;
            return $link;
        }
        else{
            return null;
        }

    }
    public function getPrimaryTitleFromID($source,$es_id){

        $a = $this->getPrimaryID($source,$es_id);
        if ($a[0]){
            $title = $this->getPrimaryTitle($a[1]);
            return $title;
        }
        else{
            return null;
        }

    }
    public function getPrimaryLinkFromID($source,$es_id){

        $a = $this->getPrimaryID($source,$es_id);
        if ($a[0]){
            $link = $this->getPrimaryLink($a[1]);
            return $link;
        }
        else{
            return null;
        }

    }
}

/*$search = new DuplicateSearch(['esIndex'=>'geo,gemma,arrayexpress','GSEID'=>"GSE2599"]);
$a = $search->getPrimaryID('gemma','57e2da715152c6433372549e');
var_dump($a);
$search->getPrimaryTitle($a[1]);*/
/*$results = $search->getSearchResult();
echo '<pre>';
print_r($results);
echo '</pre>';
//var_dump($results);
$repositoryHits = $results['hits']['total'];
var_dump($repositoryHits);*/

