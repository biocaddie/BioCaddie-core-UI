<?php
require_once 'config.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';

/*$datatype_index = ['protein'=>'pdb_v2',
                   'phenotype'=>'phenodisco',
                   'gene_expression'=>'geo',
                   'gene expression'=>'geo'];

                   $es_index = 'pdb_v2,geo,phenodisco';*/


                   if (is_ajax()) {
  if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
    $action = $_GET["action"];

    $current_repository=Null;
    // for each repository
    if(isset($_GET['query']) and strlen($_GET['query'])>0){
      $q=$_GET['query'];
      $total_num = 0;
      
      foreach($all_repositories as $repository){
        $search_repository = new ElasticSearch();
          $search_repository->search_fields = $repository->search_fields;//['_all'];//$repository->search_fields;
          $search_repository->facets_fields = $repository->facets_fields;
          $search_repository->query = $_GET['query'];
          $search_repository->filter_fields = [];
          $search_repository->es_index = $repository->index;
          $search_repository->es_type = $repository->type;
          $result = $search_repository->get_search_result();
          $repository->num = $result['hits']['total'];
          $total_num = $total_num + $repository->num;


          // facet
        /*  $current_repository=$repository;
          $query = $search_repository->get_search_result();
          $current_query = $query;
          $facets_filters = get_facets($current_query);

          $fianlResult[$repository->show_name]=array(array('repository' => $repository , 'facet' => $facets_filters));
          */
        }

        
        

        $search_all_repository = new ElasticSearch();
    $search_all_repository->search_fields = $all_search_fields;//$PDB_data->search_fields;//$all_search_fields;//['_all'];
    $search_all_repository->facets_fields = $repository->facets_fields;
    $search_all_repository->query = $_GET['query'];
    $search_all_repository->filter_fields = [];
    $search_all_repository->es_index = $es_index;
    $search_all_repository->es_type = '';
   // $search_all_repository->offset = $offset;
    $all_result = $search_all_repository->get_search_result();
    $total_num1 = $all_result['hits']['total'];
    
    $all_items = $all_result['hits']['hits'];
    
    
  }
  else{
    $q = '';
  }


//for datasets href and number

  $href = '"datasource.php?query='.$q.'"';
  $all_num = '('.($total_num).')';
  $result_href = '';



//caculate each datatypes item number
  $datatypes_num = [];
  foreach($datatypes as $datatype){
    $dataindex = $datatype_index[$datatype];
    $c = 0;
    foreach($all_repositories as $repository){
      if($repository->index==$dataindex){
        $c = $c + $repository->num;
      }
    }
    $datatypes_num[$datatype]=$c;
  }

  $fianlResult = array('repository' => $all_repositories , 'datatypes' => $datatypes_num);
  
  echo json_encode($fianlResult);
  //echo json_encode($datatypes_num);
  //echo json_encode($facets_filters);
  
}
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>
