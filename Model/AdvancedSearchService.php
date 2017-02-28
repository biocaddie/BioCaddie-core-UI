<?php

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/Repositories.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/SearchBuilder.php';

$queryBody = $_GET['data'];

if (is_ajax() && isset($queryBody)) {
    $searchType = explode(")",$queryBody)[0];
    $searchType =explode("(",$searchType)[1];

    $query=explode(")",$queryBody)[1];
    $pattern = '/(AND|OR|NOT)/';

    $queryTokens=preg_split( $pattern, $query,-1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

    $repositoryObj = new Repositories();

    $total_num = 0;
    $total_time=0;
    foreach ($repositoryObj->getRepositories() as $repository) {

        $result=getBoolSearchResult($queryTokens,$repository);
        $total_num=$total_num+$result['hits']['total'];
        $total_time=$total_time+$result['took']/1000;
    }
    echo json_encode(array("num"=>$total_num,"time"=>$total_time,'query'=>$queryBody));

}

// Function to check if the request is an AJAX request
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function getBoolSearchResult($queryTokens,$repository){
    $body=generateBoolBody($queryTokens,$repository);
    return generateBoolResult($body,$repository);
}

function generateBoolResult($body,$repository){
    global $es;

    $params['index']=$repository->index;
    $params['type']=$repository->type;
    $params['body']=$body;

    $result = $es->search($params);

    return $result;
}

function generateBoolBody($queryTokens,$repository){
    $searchQuery=generateQuery($queryTokens,$repository);

    $body['query']=$searchQuery;
    return $body;
}

function generateQuery($queryTokens,$repository){

    $query_part=array('bool'=>array('must'=>array(),
                                    'should'=>array(),
                                    'must_not'=>array()));

    $length=count($queryTokens);

    for($i=0;$i<$length;$i++){
        if($i%2==0){
            // Get query term
            preg_match('/"([^"]+)"/', $queryTokens[$i], $mq);
            $quertterm=$mq[1];


            // Get search fields
            preg_match_all("/\[(.*)\]/", $queryTokens[$i], $mo);
            $queryfields=strtolower(trim($mo[0][0],"\[\]"));


            if($queryfields=="all search fields"){
                $queryfieldsArray=$repository->search_fields;
            }

            $operator="AND";
            // Get operator
            if($i>0){
                $operator=$queryTokens[($i-1)];
            }


            switch($operator){
                case "AND":
                    if($queryfields=="all search fields"){
                        array_push($query_part['bool']['must'],array('multi_match'=>array('query'=>$quertterm,'fields'=>$queryfieldsArray)));
                    }else{
                        array_push($query_part['bool']['must'],array('match'=>array($queryfields=>$quertterm)));
                    }

                    break;
                case "OR":
                    if($queryfields=="all search fields"){
                        array_push($query_part['bool']['should'],array('multi_match'=>array('query'=>$quertterm,'fields'=>$queryfieldsArray)));
                    }else{
                        array_push($query_part['bool']['should'],array('match'=>array($queryfields=>$quertterm)));
                    }

                    break;
                case "NOT":
                    if($queryfields=="all search fields"){
                        array_push($query_part['bool']['must_not'],array('multi_match'=>array('query'=>$quertterm,'fields'=>$queryfieldsArray)));
                    }else{
                        array_push($query_part['bool']['must_not'],array('match'=>array($queryfields=>$quertterm)));
                    }

                    break;
                default;
            }
        }
    }

    return $query_part;
}

/*
function fieldsExpansion($queryField,$repository){
    $queryfieldsArray = array();
    switch($queryField){
        case "all search fields":
            $queryfieldsArray=$repository->search_fields;
            break;
        case "title":
            $queryfieldsArray = array("dataItem.title","title");
            break;
        case "author":
            $queryfieldsArray = array("author");
            break;
        case "description":
            $queryfieldsArray = array(" dataItem.description","desc","description");
            break;
        case "repository":
            $queryfieldsArray = array(" dataItem.description","desc","description");
            break;
        default:
            $queryfieldsArray = array($queryField);
    }

    return $queryfieldsArray;
}*/


