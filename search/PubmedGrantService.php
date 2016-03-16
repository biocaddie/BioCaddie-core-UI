<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 12/21/15
 * Time: 3:15 PM
 */

require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/../config/datasources.php';

class GrantSearch extends ElasticSearch
{
    // Generates the main body of the query.
    private $grantID;
    public $rowsPerPage = 1000;
    protected function generateQuery(){

        $a = str_replace(' ','*',$this->grantID);
        $a = str_replace('-','*',$a);

        if(preg_match('/^[A-Za-z]{2}[0-9]{5}/',substr($a,-7))){
            $a=substr($a,0,strlen($a)-5)."0".substr($a,strlen($a)-5);
        }

        $query_part = [
            'bool'=>[
                'must'=>["wildcard"=>
                    ["grant.project_num"=>'*'.$a.'*']
                ]
            ]
        ];
        return $query_part;
    }
    protected function generate_sort() {
        $sort = ['FY' => ['order' => 'desc', 'missing' => '_last', 'unmapped_type' => 'string']];
        return $sort;
    }
    public function setGrantID($grantID){
        $this->grantID = $grantID;
    }
    protected function generateSearchBody() {
        $facets = $this->generateFacets();

        $search_query = $this->generateQuery();

        $sort = $this->generate_sort();
        $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
            'size' => $this->rowsPerPage,
            'query' => $search_query,
            'highlight' => $this->highlight
        ];
        if (count($facets) > 0) {
            $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
                'size' => $this->rowsPerPage,
                'query' => $search_query,
                'highlight' => $this->highlight,
                'aggs' => $facets //'facets'=>$facets
            ];
        }
        if (count(array_keys($sort) >= 1)) {
            $body['sort'] = $sort;
        }

        return $body;
    }
}


class PubmedGrantService
{
    private $grants;
    private $pmid;

    private function loadParameters()
    {
        $this->pmid = filter_input(INPUT_GET, "pmid", FILTER_SANITIZE_STRING);
        if ($this->pmid === NULL || strlen($this->pmid) == 0) {
            return false;
        }
    }
    function __construct() {
        $this->loadParameters();
        $this->getPubmedGrant();
    }
    public function getPubmedGrant($pmid=Null)

    {   if(!$pmid) {
            $pmid = $this->pmid;
        }
        $urlBase = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=";#20509765'26570998'
        $grantArray = array();
        $url = $urlBase . $pmid . '&retmode=xml';
        $resultPage = file_get_contents($url);

        $xmlArray = simplexml_load_string($resultPage) or die("Error: SimpleXML cannot create object");
        //print sizeof($xmlArray->PubmedArticle->MedlineCitation->Article->GrantList->Grant);
        $grants = $xmlArray->PubmedArticle->MedlineCitation->Article->GrantList->Grant;
        $this->grants = $grants;
        foreach ($grants as $grant) {
            $name = $this->buildName($grant);
            array_push($grantArray, $name);

        }
            return $grantArray;
    }


    public function buildName($grant){
        $name = '';
        if (isset($grant->GrantID)) {
            $name = $name . $grant->GrantID;
        }
        if (isset($grant->Acronym)) {
            $name = $name . '/' . $grant->Acronym;
        }
        if (isset($grant->Agency)) {
            $name = $name . '/' . $grant->Agency;
        }
        if (isset($grant->Country)) {
            $name = $name . '/' . $grant->Country;
        }
        if (0 === strpos($name, '/')){
            $name = substr($name,1);
        }
        return $name;
    }

    public function search_grant_info()
    {
        $grants_details = array();
        foreach ($this->grants as $grant) {
            $name = $this->buildName($grant);
            $grants_details[$name]=[];

            if (isset($grant->Agency)) {
                if (strpos($grant->Agency, 'NIH') !== false) {
                    $search = new GrantSearch();
                    $search->setGrantID($grant->GrantID);

                    $search->facets_fields = [];
                    $search->filter_fields = [];
                    $search->es_index = 'grant';
                    $results = $search->getSearchResult();

                    $repositoryHits = $results['hits']['total'];
                    if($repositoryHits>0){
                        $newresult = $results['hits']['hits'];
                        $project_detail_list = $this->filter_first_grant_for_the_same_project_num($newresult);
                        $grants_details[$name]=$project_detail_list;
                    }

                }
            }

        }

        return $grants_details;
    }
    public function filter_first_grant_for_the_same_project_num($results){
        $project_list = array();
        $project_detail_list = array();
        $out = array();
        foreach($results as $result){
            $project_num = $result['_source']['project_num'];
            $id = $result['_source']['ID'];
            if(!isset($project_list[$project_num])){
                $project_list[$project_num]=$id;
                $project_detail_list[$project_num]=$result['_source'];
            }
            else{
                if($id<$project_list[$project_num]){
                    $project_list[$project_num]=$id;
                    $project_detail_list[$project_num]=$result['_source'];
                }
            }
        }
        foreach(array_keys($project_detail_list) as $key){
            array_push($out,$project_detail_list[$key]);
        }
        return $out;
    }
}
?>