<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 3/9/16
 * Time: 1:38 PM
 */
require_once dirname(__FILE__) . '/config/datasources.php';
require_once dirname(__FILE__) . '/Search/ElasticSearch.php';

Class NLPSearch extends Elasticsearch
{
    private $url = 'http://localhost:8080/simple-webapp/cdr';
    public $es_index = 'arrayexpress_030916nlp';


    private function get_NLP($data)
    {
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data;
    }

    protected function generateQuery()
    {
        if (count(array_keys($this->filter_fields)) < 1) {
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
        echo '<pre>';
        print_r($query_part);
        echo '</pre>';
        return $query_part;
    }


    private function generateQueryPart()
    {

        $NLP_data = $this->get_NLP($this->query);

        var_dump($NLP_data);

        $query_part = [['multi_match' => [
            'query' => $this->query,
            'fields' => $this->search_fields,
            'operator' => 'and',
            'boost'=>'1']]];
       $query_string = ["query_string"=>[
           'query'=>'(NLP_fields.DiseaseID:'.$NLP_data['DiseaseID'][0].')AND(NLP_fields.ChemcialID:'.$NLP_data['ChemcialID'][0].')',
           'boost'=>'2'
       ]];

        array_push($query_part,$query_string);

        echo '<pre>';
        print_r($query_part);
        echo '</pre>';
        return $query_part;

    }

}

$NERSearch = new NLPSearch();
$NERSearch->search_fields = ['dataItem.description','dataItem.title'];

$NERSearch->facets_fields = [];
$NERSearch->filter_fields = [];
$NERSearch->query = "glucose and lung cancer";
$result = $NERSearch->getSearchResult();
$repositoryHits = $result['hits']['total'];
echo $repositoryHits;
echo '<pre>';
print_r($result['hits']['hits']);
echo '</pre>';

?>