<?php

/**
 * Integration of terminology server.
 * User: xchen2
 * Date: 12/7/15
 * Time: 2:53 PM
 */
require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';

error_reporting(0);
ini_set('display_errors', 0);


class TermExpansonData
{

    protected $originalTerm;
    protected $originalumlsID;
    protected $umlsID;
    protected $synonym;
    protected $relation;

    /**
     * @return mixed
     */
    public function getOriginalTerm()
    {
        return $this->originalTerm;
    }

    /**
     * @param mixed $originalTerm
     */
    public function setOriginalTerm($originalTerm)
    {
        $this->originalTerm = $originalTerm;
    }

    public function getOriginalumlsID()
    {
        return $this->originalumlsID;
    }

    /**
     * @param mixed $originalumlsID
     */
    public function setOriginalumlsID($originalumlsID)
    {
        $this->originalumlsID = $originalumlsID;
    }

    /**
     * @return mixed
     */
    public function getUmlsID()
    {
        return $this->umlsID;
    }

    /**
     * @param mixed $umlsID
     */
    public function setUmlsID($umlsID)
    {
        $this->umlsID = $umlsID;
    }

    /**
     * @return mixed
     */
    public function getSynonym()
    {
        return $this->synonym;
    }

    /**
     * @param mixed $synonym
     */
    public function setSynonym($synonym)
    {
        $this->synonym = $synonym;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param mixed $relation
     */
    public function setRelation($relation)
    {
        $this->relation = $relation;
    }
    public function printExpansionData(){
        print 'originalTerm: '.$this->originalTerm.'<br>';
        print 'originalumlsID: '.$this->originalumlsID.'<br>';
        print 'umlsID: '.$this->umlsID.'<br>';
        print 'synonym: ';
        print_r($this->synonym);
        print '<br>';
        print 'relation: '.$this->relation.'<br>';
        print '<br>';
    }
}
class ExpansionTerms
{
    protected $originalTerm;
    protected $originalumlsID;

    /**
     * @return mixed
     */

    public function getOriginalTerm()
    {
        return $this->originalTerm;
    }

    /**
     * @param mixed $originalTerm
     */
    public function setOriginalTerm($originalTerm)
    {
        $this->originalTerm = $originalTerm;
    }

    public function getOriginalumlsID()
    {

        return $this->originalumlsID;
    }

    /**
     * @param mixed $originalumlsID
     */
    public function setOriginalumlsID($originalTerm)
    {
        $search = new ElasticSearch();
        $search->query = $originalTerm;
        $search->search_fields = ['term'];
        $search->facets_fields = [];
        $search->filter_fields = [];
        $search->es_index = 'terms';

        $result = $search->getSearchResult();
        $repositoryHits = $result['hits']['total'];
        $this->originalumlsID = '';
        if($repositoryHits>0){
            //$this->originalumlsID = 'umls:_'.$result['hits']['hits'][0]['_source']['cuis'][0];
            $this->originalumlsID = $result['hits']['hits'][0]['_source']['cuis'];
        }
       //var_dump($result['hits']['hits'][0]['_source']['cuis']);
    }
    public function get_umlsId_info1()
    {
        $url = "http://localhost:9000/scigraph/graph/neighbors/".$this->getOriginalumlsID(); #umls:_C0242379

        if (substr(php_uname(), 0, 7) == "Windows") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $json = curl_exec($ch);
            curl_close($ch);
        } else {
            $json = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $url);
        }

        $data = json_decode($json, true);
        $nodes =$data["nodes"];
        foreach($nodes as $node) {
            if ($node['id'] == $this->getOriginalumlsID()) {
                return $node;
            }
        }
        return Null ;
    }
    public function get_umlsId_info($cuiID)
    {
        $url = "http://localhost:9000/scigraph/graph/neighbors/umls:_".$cuiID; #umls:_C0242379

        if (substr(php_uname(), 0, 7) == "Windows") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $json = curl_exec($ch);
            curl_close($ch);
        } else {
            $json = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $url);
        }

        $data = json_decode($json, true);
        $nodes =$data["nodes"];
        foreach($nodes as $node) {
            if ($node['id'] == 'umls:_'.$cuiID) {
                return $node;
            }
        }
        return Null ;
    }

    private function parse_node($node){
        $synonyms = array();
        if(isset($node["id"]) && isset($node['meta']['synonym'])){
            $synonyms=$node['meta']['synonym'];
        }

        return $synonyms;
    }
    public function get_expansion_query_old(){
        $expansionquery = array();

        $relations = $this->get_umlsId_info();
        foreach($relations as $relation){
            $expansionquery = array_merge($expansionquery,$relation->getSynonym());

        }
        $expansionquery = array_unique($expansionquery);
        return $expansionquery;
    }
    public function get_expansion_query(){
        $cuiIDs=$this->getOriginalumlsID();
        $expansionquery=[];
        foreach($cuiIDs as $cuiID){
            $node = $this->get_umlsId_info($cuiID);
            $expansionquery = array_merge($expansionquery,$this->parse_node($node));
            //$expansionquery = array_slice($this->parse_node($node),0,5); //limit synonyms to 5
        }
        $expansionquery=array_unique($expansionquery);
        return $expansionquery;
    }
}
?>
