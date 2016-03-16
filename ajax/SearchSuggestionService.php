<?php

require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/../search/Repositories.php';
require_once dirname(__FILE__) . '/../search/ElasticSearch.php';

$query = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);
if (is_ajax() && isset($query)) {
    $total_num = 0;
    $repositoryObj = new Repositories();

    $search_repository = new ElasticSearch();
    $search_repository->search_fields = $repositoryObj->getSearchFields();
    $search_repository->facets_fields = ['_index'];
    $search_repository->facet_size = 20;
    $search_repository->query = $query;
    $search_repository->filter_fields = [];
    $search_repository->es_index = getElasticSearchIndexes();
    $search_repository->es_type = '';
    $esResults= $search_repository->getSearchResult();
    $repositories_counts = [];

    foreach ($esResults['aggregations']['_index']['buckets'] as $bucket) {
        $key = explode('_', $bucket['key'])[0];
        $repositories_counts[$key] = $bucket['doc_count'];
    }

    foreach ($repositoryObj->getRepositories() as $repository) {

        /*$search_repository = new ElasticSearch();
        $search_repository->search_fields = $repository->search_fields;
        $search_repository->facets_fields = $repository->facets_fields;
        $search_repository->query = $query;
        $search_repository->filter_fields = [];
        $search_repository->es_index = $repository->index;
        $search_repository->es_type = $repository->type;
        $result = $search_repository->getSearchResult();

        $repository->num = $result['hits']['total'];
*/
        if(array_key_exists($repository->index,$repositories_counts)) {
            $repository->num = $repositories_counts[$repository->index];
        }
        else {
            $repository->num = 0;
        }
    //    $total_num = $total_num + $repository->num;
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

   /* $search_all_repository = new ElasticSearch();
    $search_all_repository->search_fields = $repositoryObj->getSearchFields();
    $search_all_repository->facets_fields = $repository->facets_fields;
    $search_all_repository->query = $query;
    $search_all_repository->filter_fields = [];
    $search_all_repository->es_index = $indexes;
    $search_all_repository->es_type = '';
    $all_result = $search_all_repository->getSearchResult();
    $total_num = $all_result['hits']['total'];
    $all_items = $all_result['hits']['hits'];*/
    $total_num = $esResults['hits']['total'];

    $all_items = $esResults['hits']['hits'];
    // For datasets href
    $href = '"search.php?query=' . $query . '"';
    $all_num = '(' . ($total_num) . ')';
    $result_href = '';

    // Caculate each datatypes item number
    $datatypes_num = [];
    foreach (getDatatypes() as $datatype) {

        $dataindex = $mappings[$datatype];

        $c = 0;
        foreach ($repositoryObj->getRepositories() as $repository) {

            if (is_array($dataindex)) {
                foreach ($dataindex as $item) {
                    if ($repository->index == $item) {
                        $c = $c + $repository->num;
                    }
                }
            } else {
                if ($repository->index == $dataindex) {
                    $c = $c + $repository->num;
                }
            }
        }
        $datatypes_num[$datatype] = $c;
    }
    $fianlResult = array('repository' => $repositoryObj->getRepositories(), 'datatypes' => $datatypes_num);
    echo json_encode($fianlResult);
}

// Function to check if the request is an AJAX request
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
