<?php

require_once 'config.php';
require_once 'pagination.php';
require_once 'helper_functions.php';
$offset = 1;

if (isset($_GET['offset'])) {
    $offset = htmlentities($_GET['offset']);
}
if (isset($_GET['query1'])) {
    $q = htmlentities($_GET['query1']);

}
if (isset($_GET['query2'])) {
    $r = htmlentities($_GET['query2']);

}

if (strlen($_GET['query3']) > 0) {
    $s = htmlentities($_GET['query3']);
    #echo $s;
    $query = $es->search([
        'index' => ['gwas3'],
        'body' => [
            'from' => ($offset - 1) * 50,
            'size' => 50,
            'query' => [
                'bool' => [
                    'must' => [
                        0 => ['multi_match' => [
                                'fields' => ['_all'],//['trait', 'platform', 'source', 'stage', 'ethnicity'],
                                'query' => $q,
                                'fuzziness' => 'AUTO',
                                'operator' => 'and',
                                'zero_terms_query' => 'all']],
                        1 => ['multi_match' => [
                                'fields' => ['_all'],//['trait', 'platform', 'source', 'stage', 'ethnicity'],
                                'query' => $r,
                                'fuzziness' => 'AUTO',
                                'operator' => 'and',
                                'zero_terms_query' => 'all']],
                        2 => ['range' => ['case_size' => ['gte' => $s]]]

                    //                                0=>['query_string'=>[
                    //                                    'default_field'=>'trait',
                    //                                    'query'=>$q]],
                    //
                           //                                1=>['query_string'=>[
                    //                                    'default_field'=>'platform',
                    //                                    'query'=>$r]],
                    ////                                2=>['range'=>['case_size'=>['gte'=>$_GET['query3']]]],
                    ],
                ]
            ],
            'facets' => [
                'tag' => [
                    'terms' => [
                        'field' => 'TI',
                        'size' => 10]
                ]
            ]
    ]]);
} else {
    $s = "";
    #echo $q;
    $query = $es->search([
        'index' => ['gwas3'],
        'body' => [
            'from' => ($offset - 1) * 50,
            'size' => 50,
            'query' => [
                'bool' => [
                    'must' => [
                        0 => ['multi_match' => [
                                'fields' => ['_all'],//['trait', 'platform', 'source', 'stage', 'ethnicity'],
                                'query' => $q,
                                'fuzziness' => 'AUTO',
                                'operator' => 'and',
                                'zero_terms_query' => 'all']],
                        1 => ['multi_match' => [
                                'fields' => ['_all'],//['trait', 'platform', 'source', 'stage', 'ethnicity'],
                                'query' => $r,
                                'fuzziness' => 'AUTO',
                                'operator' => 'and',
                                'zero_terms_query' => 'all']],
                    #2=>['range'=>['case_size'=>['gte'=>$s]]]
                    //                                0=>['query_string'=>[
                    //                                    'default_field'=>'trait',
                    //                                    'query'=>$q]],
                    //
                           //                                1=>['query_string'=>[
                    //                                    'default_field'=>'platform',
                    //                                    'query'=>$r]],
                    ////                                2=>['range'=>['case_size'=>['gte'=>$_GET['query3']]]],
                    ],
                ]
            ],
            'facets' => [
                'tag' => [
                    'terms' => [
                        'field' => 'TI',
                        'size' => 10]
                ]
            ]
    ]]);
}

if ($query['hits']['total'] >= 1) {

    $results = $query['hits']['hits'];
}

?>

<?php include 'gwas_result_template.php'; ?>

