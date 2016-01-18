<?php
require_once 'config.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';

session_start();

// To track user's search history
if(!isset($_SESSION["history"])){
  $_SESSION["history"]=array();
}


$search_database= new ElasticSearch();
$search_database->search_fields = [$_GET['idName']];
$search_database->query = $_GET['id'];
$search_database->filter_fields = [];

$repository=Null;
foreach($all_repositories as $repository){
	if($repository->id==$_GET['sourceid']){
		$search_database->es_index = $repository->index;
		$search_database->es_type = $repository->type;
		break;
	}
}
$core_fields = $repository->core_fields;

$body = $search_database->generate_search_body();
$result = $search_database->get_search_result();

$result = $result['hits']['hits'][0]['_source'];
//print_r($result);

$show_results = [];
foreach($core_fields as $field){
	
	$keys = explode('.',$field);
	if(count($keys)==1){
		if(isset($result[$field])){
			$string = $result[$field];
			if(is_array($result[$field])){
					//$string = implode(', ',$result[$field1][$field2]);
				$string=json_encode($result[$field]);
				$string = str_replace('{','',$string);
				$string = str_replace('}','',$string);
				$string = str_replace('[','',$string);
				$string = str_replace(']','',$string);
				$string = str_replace('"',' ',$string);
			}
			$show_results[$field]=$string;
		}
		else{
			$show_results[$field]='';
		}
	}
	if(count($keys)==2){
		$field1=$keys[0];
		$field2=$keys[1];
		if(isset($result[$field1][$field2])){
			$string = $result[$field1][$field2];
			if(is_array($result[$field1][$field2])){
				//echo print_r($result[$field1][$field2]);
				//$string = implode(', ',$result[$field1][$field2]);
				$string=json_encode($result[$field1][$field2]);
				$string = str_replace('{','',$string);
				$string = str_replace('}','',$string);
				$string = str_replace('[','',$string);
				$string = str_replace(']','',$string);
				$string = str_replace('"',' ',$string);
			}
			$show_results[$field]=$string;
		}
	}
	elseif(count($keys)==3){
		$field1=$keys[0];
		$field2=$keys[1];
		$field3=$keys[2];
		if(isset($result[$field1][$field2][$field3])){
			$string = $result[$field1][$field2][$field3];
			if(is_array($result[$field1][$field2][$field3])){
				//$string = implode(', ',$result[$field1][$field2][$field3]);
				$string=json_encode($result[$field1][$field2][$field3]);
				$string = str_replace('{','',$string);
				$string = str_replace('}','',$string);
				$string = str_replace('[','',$string);
				$string = str_replace(']','',$string);
				$string = str_replace('"',' ',$string);

			}
			$show_results[$field]=$string;
		}
		
		
	}
}
$show_results[$repository->link_field]='<a href="'.$repository->source.$show_results[$repository->link_field].'" target="_blank">'.$show_results[$repository->link_field].'</a>';
?>

<?php include './templates/single_item.php';?>

