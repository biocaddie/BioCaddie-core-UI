<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/12/16
 * Time: 9:51 AM
 */
require_once dirname(__FILE__) . '/ElasticSearch.php';

class GrantSearch extends ElasticSearch
{

    private $grantID;
    public $rowsPerPage = 1;
    /*
     * generate query part for grant search
     * @return array(string)
     */
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
    /*
     * generate sort part of search, grant are sort by time
     * @return array(string)
     */
    protected function generate_sort() {
        $sort = ['FY' => ['order' => 'asc', 'missing' => '_last', 'unmapped_type' => 'string']];
        return $sort;
    }

    public function setGrantID($grantID){
        $this->grantID = $grantID;
    }
    /*
     * generate search body for ES
     * @return array(string)
     */
    protected function generateBody() {
        $search_query = $this->generateQuery();
        $sort = $this->generate_sort();
        $body = ['from' => ($this->offset - 1) * $this->rowsPerPage,
            'size' => $this->rowsPerPage,
            'query' => $search_query,
            'highlight' => $this->highlight
        ];
        if (count(array_keys($sort) >= 1)) {
            $body['sort'] = $sort;
        }

        return $body;
    }
    public function __construct($input_array){
        parent::__construct($input_array);
        $this->setGrantID($input_array['GrantID']);
        $this->setSearchResult();

    }
}
