<?php
require_once 'config.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';

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
//print_r($result);

$result = $result['hits']['hits'][0]['_source'];

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
<?php include 'header.php';?>
<br>
<!-- go back to previsous page-->
<a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="btn btn-info btn-primary">
          <span class="glyphicon glyphicon-arrow-left"></span> Previous page
</a><br>
<!--show title-->
<h3 class='text-center'><?php echo $show_results[$repository->datasource_headers[0]];?></h3>
<div class='text-center'><h4>From <a class='text-center' href="<?php echo $repository->source_main_page;?>" target="_blank"><?php echo $repository->show_name; ?></a></h4></div>

<?php if($_GET['sourceid']=='0002'){?>  <!--hard code for expand the table for PDB-->
    <table class="table table-striped table-hover dashboard">
	<col width="130">
   <thead>

   </thead>
    <tbody>  
    	
   <?php foreach(array_keys($show_results) as $key):
       if($key==$repository->datasource_headers[0]){
       	continue;
       }
       if(strpos($key,'dataItem')!== false){?>
			       	<tr>
			         <td> 
			            <?php echo $repository->core_fields_show_name[$key];?>
			         </td>
			         <td> 
			            <?php echo $show_results[$key];?>
			         </td>
			        </tr>
         <?php }  
        else{?>
			     <?php if(strlen($show_results[$key])==0){//echo $key;?>
			           <tr class="clickable" data-toggle="collapse">
			           	
			               <td colspan="2"><i class="glyphicon glyphicon-collapse-down"></i> <?php echo $repository->core_fields_show_name[$key];?></td>
			            </tr>


			        <?php }
			        else{ ?>
					      <tr class="collapse">
					         <td> 
					            <?php echo $repository->core_fields_show_name[$key];?>
					         </td>
					         <td> 
					            <?php echo $show_results[$key];?>
					         </td>
					    </tr>

			   <?php } } 
       endforeach; ?>
   
   </tbody>   
 </table>



<?php }else{ ?>


<table class="table table-striped">
	<col width="130">
   <thead>

  </thead>
    <tbody>  
    	
   <?php foreach(array_keys($show_results) as $key):
       if($key==$repository->datasource_headers[0]){
       	continue;
       }
       ?>
    <tr>
         <td> 
            <?php //echo $key;?>
            <?php echo $repository->core_fields_show_name[$key];?>
         </td>
         <td> 
            <?php echo $show_results[$key];?>
         </td>
    </tr>
   <?php endforeach;?>

   </tbody>
 </table>

<?php }?>
<?php
if(count(array_keys($show_results))<=30){
	for($i=0;$i<30-count(array_keys($show_results));$i++){
		echo '<br>';
}
}
?>

 <?php include 'footer.php';?>