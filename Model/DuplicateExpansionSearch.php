<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/7/16
 * Time: 2:34 PM
 * Class to get synonyms,construct ES query and get ES result
 */
require_once 'ExpansionSearch.php';
class DuplicateExpansionSearch extends ExpansionSearch
{
    /*
     * generate ES query part according to synonyms and query
     * @return array(string)
     */
    protected $synonyms;
    protected $synonymsArray=[];
    public function __construct($input_array)
    {
        parent::__construct($input_array);
        $this->setSearchResult();
    }


    /*
     * generate ES query part according to synonyms and query
     * @return array(string)
     */


    protected function generateQueryPart()

    {
        $query_part =[['bool'=>["must"=> [
            ['multi_match' => [
                'query' => $this->query,
                'fields' => $this->searchFields,
                'operator' => 'and',
                'type' => $this->queryType]],

            ["constant_score" => [
                "filter" =>[
                    "missing" => [ "field"=>"datasetDistributions.primary" ]
                ]]
            ]]]]]
        ;
        //$expan_query = $this->getSynonyms();
        //$expan_query=[];

        if(sizeof($this->synonymsArray)==0){
            return $query_part;
        }
        $all_synsquery = ['bool'=>['must'=>[]]];
        foreach(array_keys($this->synonymsArray) as $key){
            $synmquery = ['bool'=>['should'=>[]]];
            foreach ($this->synonymsArray[$key] as $query ) {
                array_push($synmquery['bool']['should'], ['bool'=>["must"=> [
                    ['multi_match' => [
                        'query' => $query,
                        'fields' => $this->searchFields,
                        'operator' => 'and',
                    ]],

                    ["constant_score" => [
                        "filter" =>[
                            "missing" => [ "field"=>"datasetDistributions.primary" ]
                        ]]
                    ]]]]);
            }
            array_push($all_synsquery['bool']['must'],$synmquery);
        }

        array_push($query_part,$all_synsquery);
        return $query_part;
    }
    /*
     * overwrite ES query part according to synonyms and query
     * @return array(string)
     */
    protected function filterSecondaryDataset(){
        $secondpart=["constant_score" => [
            "filter" =>[
                "missing" => [ "field"=>"datasetDistributions.primary" ]
            ]]];
        return $secondpart;
    }

    protected function generateQuery()
    {
        if (count(array_keys($this->filterFields)) < 1 && $this->year=="") {
            $query_part = [
                'bool' => [
                    'should' => $this->generateQueryPart()
                ]
            ];
        } else {
            $filter = $this->generateFilter();
            $query_part = [
                'filtered' => [
                    'query' => [
                        'bool' => [
                            'should' => $this->generateQueryPart()
                        ]
                    ],
                    'filter' => $filter
                ]
            ];
        }
    /*    echo '<pre>';
        print_r($query_part);
        echo '</pre>';*/
        return $query_part;
    }








}

?>
