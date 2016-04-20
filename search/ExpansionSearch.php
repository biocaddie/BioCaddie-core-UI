<?php

/**

 * User: xchen2
 * Date: 12/8/15
 * Time: 11:25 AM
 */
error_reporting(0);
ini_set('display_errors', 0);

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/terminology.php';


class ExpansionSearch extends ElasticSearch
{
    private $terminologyquery;

    public function getTerminologyquery()
    {

        $ExpansionTerms = new ExpansionTerms();
        $this->update_query_string();
        $ExpansionTerms->setOriginalTerm($this->query);
        $ExpansionTerms->setOriginalumlsID($this->query);
        $expan_query = $ExpansionTerms->get_expansion_query();

        return $expan_query;

    }
    public function setTerminologyquery($terminologyquery)
    {
        $this->terminologyquery = $terminologyquery;
    }
    // Generates the main body of the query.
    protected function generateQuery(){
        if(count(array_keys($this->filter_fields))<1){
            $query_part = [
                'bool'=>[
                    'should'=>$this->generateQueryPart()
                ]
            ];
        }
        else{
            $filter = $this->generateFilter();
            $query_part = [
                'filtered'=>[
                    'query'=>[
                        'bool'=>[
                            'should'=>$this->generateQueryPart()
                        ]
                    ],
                    'filter'=>$filter
                ]

            ];
        }
        return $query_part;
    }
    private function cleanUp($text)
    {
        $text = str_replace(array('(', ')', '-'), ' ', $text);
        return $text;
    }


    private function generateQueryPart1() {
        $expan_query = $this->getTerminologyquery();
        array_push($expan_query,$this->query);
        $q = array();
        foreach($expan_query as $query){
            array_push($q, implode('" AND "', explode(' ', $query)));
        }
        $query = '("' . implode('" ) OR ("', $q ) . '")';
        $query_part = ['query_string'=>[
            'fields'=>$this->search_fields,
            'query'=> $query ]];
        return $query_part;
    }
    private function generateQueryPart(){
        $expan_query = $this->getTerminologyquery();
        $query_part = [['multi_match'=>[
            'query'=>$this->query,
            'fields'=>$this->search_fields,
            'operator'=>'and',
            'type' => $this->querytype]]];
        foreach($expan_query as $query){
            array_push($query_part,['multi_match'=>[
                'query'=>$query,
                'fields'=>$this->search_fields,
                'operator'=>'and']]);
        }
        return $query_part;

    }
}
