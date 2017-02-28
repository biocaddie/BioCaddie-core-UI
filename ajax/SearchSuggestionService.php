<?php

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/../Model/Repositories.php';
require_once dirname(__FILE__) . '/../Model/ElasticSearch.php';
require_once dirname(__FILE__) . '/../Model/ExpansionSearch.php';
require_once dirname(__FILE__) . '/../Model/BooleanSearch.php';
require_once dirname(__FILE__) . '/../Model/Utilities.php';

/*
 * Generate search results for the search suggestion function on the landing page
 * @return: array(array(string))
 * [
 * {
 *  "datatypes": data type name
 *  "typeNum": number of search results
 *  "repository":
 *          [
 *              {
 *                  "id": id of the repository
 *                  "num": number of search results from this repo
 *                  "repoShowName": name of this repo
 *              },
 *              {
 *              }
 *              ...
 *          ]
 * },
 * {
 * }
 * ...
 * ]
 * */

$query = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);

if (is_ajax() && isset($query)) {
    $total_num = 0;
    $repositoryObj = new Repositories();

    // prepare parameters
    $input_array = [];
    $input_array['esIndex'] =getElasticSearchIndexes();
    $input_array['esType'] ='';
    $input_array['query'] = $query;
    $input_array['searchFields'] = $repositoryObj->getSearchFields();
    $input_array['aggsFields'] = ['_index'];
    $input_array['facetSize'] = 300;
    $input_array['filterFields'] = [];

    if (isBoolSearch($query) == 0) {
        $search_repository =  new ExpansionSearch($input_array);
    }else{
        $search_repository =  new BooleanSearch($input_array);
    }

    // Get search results
    $esResults= $search_repository->getSearchResult();

    $repositories_counts = [];
    foreach ($esResults['aggregations']['_index']['buckets'] as $bucket) {
        $key = explode('_', $bucket['key'])[0];
        $repositories_counts[$key] = $bucket['doc_count'];
    }

    foreach ($repositoryObj->getRepositories() as $repository) {
        if(array_key_exists($repository->index,$repositories_counts)) {
            $repository->num = $repositories_counts[$repository->index];
        }
        else {
            $repository->num = 0;
        }
    }

    $elasticSearchIndexes = '';
    $mappings = getDatatypesMapping();
    foreach ($mappings as $index) {
        if (is_array($index)) {
            foreach ($index as $item) {
                $elasticSearchIndexes .= $item . ',';
            }
        } else {
            $elasticSearchIndexes .= $index . ',';
        }
    }
    $indexes = substr($elasticSearchIndexes, 0, -1);
    $total_num = $esResults['hits']['total'];
    $all_items = $esResults['hits']['hits'];

    // Generate hyperlink for this query
    $href = '"search.php?query=' . $query . '"';
    $all_num = '(' . ($total_num) . ')';
    $result_href = '';

    // calculate number of search research from each data type
    $finalResult=[];

    foreach (getDatatypes() as $datatype) {
        $repo_array = [];
        $dataindex = $mappings[$datatype];

        $c = 0;
        foreach ($repositoryObj->getRepositories() as $repository) {
            if (is_array($dataindex)) {
                foreach ($dataindex as $item) {
                    if ($repository->index == $item) {
                        $c = $c + $repository->num;
                        array_push($repo_array,array('id'=>$repository->id,'repoShowName'=>$repository->repoShowName, 'num'=>$repository->num));
                    }
                }
            } else {
                if ($repository->index == $dataindex) {
                    $c = $c + $repository->num;
                    array_push($repo_array,array('id'=>$repository->id,'repoShowName'=>$repository->repoShowName, 'num'=>$repository->num));
                }
            }
        }
        array_push($finalResult,array("datatypes"=>$datatype,"typeNum"=>$c,"repository"=>$repo_array));
    }
    echo json_encode($finalResult);
}

// Function to check if the request is an AJAX request
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

