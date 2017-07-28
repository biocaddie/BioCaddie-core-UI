<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/12/16
 * Time: 9:51 AM
 */
require_once dirname(__FILE__) . '/ElasticSearch.php';

class CDESearch extends ElasticSearch
{

    public $rowsPerPage = 1;
    public $CDE=Null;
    /*
     * generate query part for grant search
     * @return array(string)
     */
    protected function generateQuery(){



        $query_part = [
            'match'=>[
                'name'=> $this->query
                ]
        ];
        return $query_part;
    }




    /*
     * generate search body for ES
     * @return array(string)
     */
    protected function generateBody() {
        $search_query = $this->generateQuery();

        $body = [
            'size' => 1,
            'query' => $search_query,

        ];

        return $body;
    }
    public function setSearchResult() {
        $this->updateQueryString();
        $body = $this->generateBody();
        $this->searchResult = $this->generateResult($body);

    }
    public function postrules($query,$cde){

        $len1 = strlen($query);
        $len2 = strlen($cde);
        if($len1/$len2>5 or (float)$len1/$len2<0.2){
            return false;
        }
        return true;
    }
    public function setCDE(){
        if ($this->searchResult['hits']['total']>0){
            foreach($this->searchResult['hits']['hits'] as $item){
                $cde = $item['_source']['name'];
                if((float)$item['_score']>=3.5){


                    if($this->postrules($this->query,$cde)){
                        $this->CDE = $cde;
                    }
                }
            }
        }
    }
    public function getCDE(){
        return $this->CDE;
    }
    public function __construct($input_array){
        parent::__construct($input_array);
        $this->setSearchResult();
        $this->setCDE();

    }
    public function constructUrl(){
        return 'search.php?searchtype=data&query=&#34;'.$this->CDE.'&#34;[Dimension]';
    }
}

