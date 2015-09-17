<?php
//some helper functions 
function get_previsoue($offset){
	//get previous offset index
  if($offset>1){
	$offset = $offset -1 ;
	 }
  return $offset;
}

function get_next($offset,$num,$N){
	// get next offset index
  if($offset<$num/$N){
	$offset = $offset +1 ;
	}
  return $offset;
}

function show_current_record_number($offset,$num,$N){
	//show the record number in the current page
  if($offset<$num/$N){
	   return ((($offset-1)*$N)+1)."-".($offset)*$N;
	}
  else{
     return ((($offset-1)*$N)+1)."-".$num;
  }
}

//get facets from the query result
function get_facets($query){
	$keys = array_keys($query['facets']);
	$result = [];
    foreach($keys as $key){
    	$terms = $query['facets'][$key]['terms'];
    	$term_array = [];
    	foreach($terms as $term){
    		$name = encode_facets_term($key,$term['term']);
    		array_push($term_array,['show_name'=>$term['term'],'name'=>$name,'count'=>$term['count']]);
    	}
    	array_push($result,['key'=>$key,
    		        'term_array'=>$term_array]);
    }
    return $result;

}

function encode_facets_term($key,$value){
     //replace space as '___'
	   //repalce . as '____'
     $value = str_replace('.','____',$value);
     $value = str_replace(' ','___',$value);
     $term = $key.':'.$value;
     return $term;
}

function convert_facets_post($string){
     $terms = explode(':',$string);
     $key = str_replace('.','_',$terms[0]);
     $value = $terms[1];
     $newString = $key.':'.$value;
     return $newString;
}

function getGitRevision()
{
    exec('git describe --all',$refs);
    return $refs[0];
}

?>