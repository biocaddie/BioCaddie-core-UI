<?php
require_once 'config.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';
require_once './templates/pagination.php';
require_once './templates/left_panel_repositories.php';

require_once './templates/recent_activity/track_history.php';


if(isset($_SESSION['filters']) && isset($_GET['offset'])){
  // $_POST = $_SESSION['filters'];
 $_POST = get_rid_of_sessionname($_SESSION['filters']);
}
else{
  //$_SESSION['filters']=$_POST ;
  $_SESSION['filters']=get_rid_of_sessionname($_POST) ;
}

//echo print_r($_POST);
$q=$_GET['query'];
$datasource=$_GET['datasource'];
//echo print_r($_POST);
$offset = 1;
if (isset($_GET['offset'])){
  $offset = $_GET['offset'];
}

$new_post = get_rid_of_sessionname($_POST);

$current_repository=Null;
foreach($all_repositories as $repository){
  $search_repository = new ElasticSearch();
        $search_repository->search_fields = $repository->search_fields;//['_all'];//$repository->search_fields;
        $search_repository->facets_fields = $repository->facets_fields;
        $search_repository->query = $_GET['query'];
        $search_repository->filter_fields = [];
        $search_repository->es_index = $repository->index;
        $search_repository->es_type = $repository->type;
        $search_repository->offset = $offset;
        $query = $search_repository->get_search_result();
        $repository->num = $query['hits']['total'];
        if($repository->id == $datasource){
         $current_repository = $repository;
         $search_repository->filter_fields = $repository->decode_filter_fields($new_post);
         $query = $search_repository->get_search_result();
         $current_query = $query;
       }
     }
     $source = $current_repository->show_name;
     $N=10;
     $results = null;
     if($current_query['hits']['total']>=1){
      $results=$current_query['hits']['hits'];
    }


    $facets_filters = get_facets($current_query);
//echo '<pre>';
//print_r($current_query);
//echo '</pre>';
    $total_num = $current_query['hits']['total'];

    $headers = $current_repository->headers;
    $show_results = $current_repository->show_table($results);

    ?>

    <?php include './templates/result.php';?>

