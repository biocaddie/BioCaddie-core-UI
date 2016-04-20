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
    {   $time_pre = microtime(true);
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;
        var_dump("execution time of get NLP: ",$exec_time);
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

    private function construct_NLP_query($NLP_data){
        $disease = $NLP_data['DiseaseID'];
        $chemical = $NLP_data['ChemcialID'];
        $gene = $NLP_data['GeneID'];
        $BP = $NLP_data['GOID'];
        $d_result = "";
        foreach($disease as $d ){
            if(strlen($d_result)==0){
                $d_result = 'NLP_fields.DiseaseID:'.$d;
            }
            else{
                $d_result = $d_result.' OR '.'NLP_fields.DiseaseID:'.$d;
            }
        }
        $c_result = "";
        foreach($chemical as $d ){
            if(strlen($c_result)==0){
                $c_result = 'NLP_fields.ChemcialID:'.$d;
            }
            else{
                $c_result = $c_result.' OR '.'NLP_fields.ChemcialID:'.$d;
            }
        }
        $bp_result = "";
        foreach($BP as $d ){
            if(strlen($bp_result)==0){
                $bp_result = 'NLP_fields.GOID:'.$d;
            }
            else{
                $bp_result = $bp_result.' OR '.'NLP_fields.GOID:'.$d;
            }
        }
        $g_result = "";
        foreach($gene as $d ){
            if(strlen($g_result)==0){
                $g_result = 'NLP_fields.GeneID:'.$d;
            }
            else{
                $g_result = $g_result.' OR '.'NLP_fields.GeneID:'.$d;
            }
        }

        $result = '';
        if(strlen($d_result)>0){
            $result = '('.$d_result.')';
        }
        if(strlen($c_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$c_result.')';
            }
            else{
                $result = '('.$c_result.')';
            }
        }
        if(strlen($bp_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$bp_result.')';
            }
            else{
                $result = '('.$bp_result.')';
            }
        }
        if(strlen($g_result)>0){
            if(strlen($result)>0){
                $result = $result.'AND('.$g_result.')';
            }
            else{
                $result = '('.$g_result.')';
            }
        }

        return $result;
    }
    private function generateQueryPart()
    {

        $NLP_data = $this->get_NLP($this->query);
        //$NLP_data = [];
        var_dump($NLP_data);
        //$query_part = [];
        $query_part = [['multi_match' => [
            'query' => $this->query,
            'fields' => $this->search_fields,
            'operator' => 'and',
            'boost'=>'1']]];
       $query_string = ["query_string"=>[
           //'query'=>'(NLP_fields.DiseaseID:'.$NLP_data['DiseaseID'][0].')AND(NLP_fields.ChemcialID:'.$NLP_data['ChemcialID'][0].')',
           'query'=>$this->construct_NLP_query($NLP_data),
           'boost'=>'2'
       ]];
        //$query_part = [];
        array_push($query_part,$query_string);
       /* foreach($NLP_data as $NLP=>$value) {
            var_dump(stripos($NLP, "ID"));
            if(sizeof($value)==0 or !stripos($NLP, "ID")){
                continue;
            }
            foreach($value as $singlevalue){
               array_push($query_part, ['multi_match' => [
                'query' => $singlevalue,
                'fields' => ['NLP_fields.'.$NLP],
                'operator' => 'and',
                'boost' => '2']]);
           }
        }*/
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
$NERSearch->query = "cancer EGFR";
$result = $NERSearch->getSearchResult();
$repositoryHits = $result['hits']['total'];
echo $repositoryHits;
echo '<pre>';
print_r($result['hits']['hits']);
echo '</pre>';
/*
$url = 'http://localhost:8080/simple-webapp/cdr';
//$data = array('data' => 'lung cancer gene expression EGFR yes or not');
$data = 'lung cancer gene expression EGFR yes or not';
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response, true);
var_dump($data);*/
?>