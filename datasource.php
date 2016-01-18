<?php
require_once './config.php';
require_once './repositories_model.php';
require_once './search_model.php';
require_once './helper_functions.php';
require_once './templates/pagination.php';
require_once './templates/left_panel_repositories.php';
require_once './templates/recent_activity/track_history.php';



if(isset($_SESSION['filters']) && isset($_GET['offset'])){
 $_POST = get_rid_of_sessionname($_SESSION['filters']);
}
else{
  $_SESSION['filters']=get_rid_of_sessionname($_POST) ;
}

$new_post = get_rid_of_sessionname($_POST);
if(count(array_keys($new_post))>0){
  $es_index = '';
  foreach(array_keys($new_post) as $key){
    if($key!='query')
      $es_index=$es_index.','.$datatype_index[$key];
  }      
}  

$N=10;
$offset = 1;
if (isset($_GET['offset'])){
  $offset = $_GET['offset'];
}


if(isset($_REQUEST['query']) and strlen($_REQUEST['query'])>0){
  $q=$_REQUEST['query'];
  $total_num = 0;
  
  foreach($all_repositories as $repository){
    $search_repository = new ElasticSearch();
          $search_repository->search_fields = $repository->search_fields;//['_all'];//$repository->search_fields;
          $search_repository->facets_fields = [];//$repository->facets_fields;
          $search_repository->query = $_REQUEST['query'];
          $search_repository->filter_fields = [];
          $search_repository->es_index = $repository->index;
          $search_repository->es_type = $repository->type;
          $result = $search_repository->get_search_result();
          $repository->num = $result['hits']['total'];
          $total_num = $total_num + $repository->num;
          

        }
        

        $search_all_repository = new ElasticSearch();
    $search_all_repository->search_fields = $all_search_fields;//$PDB_data->search_fields;//$all_search_fields;//['_all'];
    $search_all_repository->facets_fields = [];
    $search_all_repository->query = $_REQUEST['query'];
    $search_all_repository->filter_fields = [];
    $search_all_repository->es_index = $es_index;
    $search_all_repository->es_type = '';
    $search_all_repository->offset = $offset;
    $all_result = $search_all_repository->get_search_result();
    $total_num1 = $all_result['hits']['total'];
    
    $all_items = $all_result['hits']['hits'];
  }
  else{
    $q = '';
  }


//for datasets href and number

  $href = '"datasource.php?query='.$q.'"';
//print_r($_POST);
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


//for showing result

  $index_type_header=[];
  foreach($all_repositories as $repository){
    $index_type_header[$repository->index.'_'.$repository->type]=[$repository->datasource_headers,$repository->source,$repository->show_name,
    $repository->id,$repository->link_field,$repository->core_fields_show_name];
    
  }

  $show_all_items = [];
  foreach($all_items as $item){
   $key =  $item['_index'].'_'.$item['_type'];
   $headers_id=$index_type_header[$key][0];

   $source1=$index_type_header[$key][1];
   $show_item = [];
   
   for($i=0;$i<sizeof($headers_id);$i++){
    $new_name = $index_type_header[$key][5][$headers_id[$i]];

    $fields = explode('.',$headers_id[$i]);
    
    if(count($fields)==2){
      if(isset($item['highlight'][$headers_id[$i]])){
       $item['_source'][$fields[0]][$fields[1]] = $item['highlight'][$headers_id[$i]];
     }
     if(is_array($item['_source'][$fields[0]][$fields[1]])){
          //$show_item[$headers_id[$i]]=implode(' ',$item['_source'][$fields[0]][$fields[1]]);
      $show_item[$new_name]=implode(' ',$item['_source'][$fields[0]][$fields[1]]);
    }
    else{
        //$show_item[$headers_id[$i]]=$item['_source'][$fields[0]][$fields[1]];
      $show_item[$new_name]=$item['_source'][$fields[0]][$fields[1]];
    }
  }
  else{
    if(isset($item['highlight'][$headers_id[$i]])){
     $item['_source'][$headers_id[$i]] = $item['highlight'][$headers_id[$i]];
   }

   if(is_array($item['_source'][$headers_id[$i]])){
        //$show_item[$headers_id[$i]]=implode(' ',$item['_source'][$headers_id[$i]]);
    $show_item[$new_name]=implode(' ',$item['_source'][$headers_id[$i]]);
  }
  else{
        //$show_item[$headers_id[$i]]=$item['_source'][$headers_id[$i]];
    $show_item[$new_name]=$item['_source'][$headers_id[$i]];
  }
}

}

$fields = explode('.',$index_type_header[$key][4]);
if(count($fields)==2){
  if(is_array($item['_source'][$fields[0]][$fields[1]])){

    $item['_source'][$fields[0]][$fields[1]]=implode(' ',$item['_source'][$fields[0]][$fields[1]]);
  }
    //$show_item['ref'] = $source1.$item['_source'][$fields[0]][$fields[1]];
  $orinial_item = str_replace('<b>','',$item['_source'][$fields[0]][$fields[1]]);
  
}
else{
 if(is_array($item['_source'][$index_type_header[$key][4]])){

  $item['_source'][$index_type_header[$key][4]]=implode(' ',$item['_source'][$index_type_header[$key][4]]);
}
     //$show_item['ref'] = $source1.$item['_source'][$index_type_header[$key][4]];
$orinial_item = str_replace('<b>','',$item['_source'][$index_type_header[$key][4]]);
     //echo $show_item['ref'];
}
$orinial_item = str_replace('</b>','',$orinial_item);
$show_item['ref'] = 'single_item.php?sourceid='.$index_type_header[$key][3].'&idName='.$index_type_header[$key][4].'&id='.$orinial_item;
$show_item['source']=$index_type_header[$key][2];
$show_item['source_ref']=$index_type_header[$key][3];

array_push($show_all_items,$show_item);

}
?>




<?php include './templates/datasource.php';?>

