<?php
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';
//echo print_r($_POST);
$datatype_index = ['protein'=>'pdb2',
                   'phenotype'=>'phenodisco',
                   'gene_expression'=>'geo',
                   'gene expression'=>'geo'];

$es_index = 'pdb2,geo,phenodisco';


session_start();

if(isset($_SESSION['filters']) && isset($_GET['offset'])){
   $_POST = $_SESSION['filters'];
}
else{
  $_SESSION['filters']=$_POST ;
}


if(count(array_keys($_POST))>0){
  $es_index = '';
  foreach(array_keys($_POST) as $key){
    $es_index=$es_index.','.$datatype_index[$key];
  }      
}  
//echo $es_index;
$N=10;
$offset = 1;
if (isset($_GET['offset'])){
      $offset = $_GET['offset'];
 }
 

if(isset($_GET['query']) and strlen($_GET['query'])>0){
      $q=$_GET['query'];
      $total_num = 0;
      
      foreach($all_repositories as $repository){
          $search_repository = new ElasticSearch();
          $search_repository->search_fields = $repository->search_fields;//['_all'];//$repository->search_fields;
          $search_repository->facets_fields = [];//$repository->facets_fields;
          $search_repository->query = $_GET['query'];
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
    $search_all_repository->query = $_GET['query'];
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

/*if(count(array_keys($_POST))>0){
  $newrepositories = [];
  foreach(array_keys($_POST) as $key){
    $key = str_replace('_',' ',$key);
    $newrepositories = array_merge($newrepositories,$repositories_datatypes[$key]);
  }
}
else{
  $newrepositories = $repositories;
}*/

//for datasets href and number

$href = '"datasource.php?query='.$q.'"';
$all_num = '('.($total_num).')';
$result_href = '';

$datatypes = ['protein','phenotype','gene expression'];

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
    $index_type_header[$repository->index.'_'.$repository->type]=[$repository->datasource_headers,$repository->source,$repository->show_name,$repository->id];
    
}

$show_all_items = [];
foreach($all_items as $item){
   $key =  $item['_index'].'_'.$item['_type'];
   $headers_id=$index_type_header[$key][0];

   $source1=$index_type_header[$key][1];
   $show_item = [];
   
   //echo end($headers_id); 
   for($i=0;$i<sizeof($headers_id)-1;$i++){
      $fields = explode('.',$headers_id[$i]);
      
      if(count($fields)==2){
        if(isset($item['highlight'][$headers_id[$i]])){
           $item['_source'][$fields[0]][$fields[1]] = $item['highlight'][$headers_id[$i]];
        }
        if(is_array($item['_source'][$fields[0]][$fields[1]])){
          $show_item[$headers_id[$i]]=implode(' ',$item['_source'][$fields[0]][$fields[1]]);
       }
        else{
        $show_item[$headers_id[$i]]=$item['_source'][$fields[0]][$fields[1]];
        }
      }
     else{
      if(isset($item['highlight'][$headers_id[$i]])){
           $item['_source'][$headers_id[$i]] = $item['highlight'][$headers_id[$i]];
        }

      if(is_array($item['_source'][$headers_id[$i]])){
        $show_item[$headers_id[$i]]=implode(' ',$item['_source'][$headers_id[$i]]);
      }
      else{
        $show_item[$headers_id[$i]]=$item['_source'][$headers_id[$i]];
      }
   }
  
 }
   
   $fields = explode('.',end($headers_id));
   
   if(count($fields)==2){
        if(is_array($item['_source'][$fields[0]][$fields[1]])){

            $item['_source'][$fields[0]][$fields[1]]=implode(' ',$item['_source'][$fields[0]][$fields[1]]);
      }
    $show_item['ref'] = $source1.$item['_source'][$fields[0]][$fields[1]];
   }
   else{


   if(is_array($item['_source'][end($headers_id)])){

      $item['_source'][end($headers_id)]=implode(' ',$item['_source'][end($headers_id)]);
   }
   $show_item['ref'] = $source1.$item['_source'][end($headers_id)];
}
   $show_item['source']=$index_type_header[$key][2];
   $show_item['source_ref']=$index_type_header[$key][3];

   array_push($show_all_items,$show_item);
                  
}


?>


<?php include 'header.php';?>
        
      <div class="col-lg-12">
        <form action='datasource.php' method='get' autocomplete='off'>
         <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for data through BioCADDIE"  name='query'>
                <div class="input-group-btn">
                <input class="btn btn-default" type='submit' value='Go!'>
              </div><!-- /btn-group -->
        </div><!--input-group-->
       </form>
      </div><!-- /.col-lg-12 -->
      
     <br>
     <br>
     <div class="row">
  			<div class="col-md-4" id="leftCol">
               <h4> Show results for</h4>
              	<ul class="nav nav-stacked" id="sidebar">
                  <?php foreach($all_repositories as $repository):?>
                    <tr><font size="4"><a href="result.php?query=<?php echo $q;?>&datasource=<?php echo $repository->id;?>"> 
                      <?php echo $repository->show_name;?> ( <?php echo $repository->num;?>)</a></font></tr></br><br>           
                  <?php endforeach;?> 
               </ul>

      <!--facets part -->
      
				 <ul class="nav nav-stacked" id="sidebar">
              <h3> Refined by Data Type</h3>
              <form  action=<?php echo $href;?> method="post" autocomplete='off'>
                  <button id="button" type="button" class="btn btn-primary" data-toggle="collapse" data-target="#datatypes">
                      <span class="glyphicon glyphicon-collapse-down"></span> DataTypes
                 </button>
               <div id="datatypes" class="collapse.in">
        
                  <?php foreach($datatypes as $tag){ 
                  
                      $flag = " ";
                      $newtag = str_replace(' ','_',$tag);
                      if(array_key_exists($newtag, $_POST)){
                          $flag = 'checked';
                      }
                  ?>
                   <div class="checkbox">
                       <label><input type="checkbox" name="<?php echo $tag;?>" <?php echo $flag;?>> <?php echo $tag.' ('.$datatypes_num[$tag].')'; ?></label>
                  </div>
                  <?php }?>
             </div>

             <br>
				      <input class="btn btn-default" type='submit' value='submit'>
				    </form>

        </ul>
      
      	</div>  
      
      		<div class="col-md-8">
                
        			
     
      <!--show result part -->
      <!-- pagination part -->

              <?php if (isset($show_all_items)) {?>
                  <h4>Displaying <?php echo show_current_record_number($offset,$total_num1,$N)?> of <?php echo $total_num1?> results for "<b><?php echo $q;?>"</b></h4>
                 <?php if ($total_num1/$N>1){?>
                      <nav>
                        <ul class="pagination">
                          <li>
                            <a href="datasource.php?query=<?php echo $_GET['query']."&offset=".get_previsoue($offset);?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>
                      <?php
                  for($i=1;$i<min(10,$total_num1 /$N);$i++){
                       ?>
                       <li><a href="datasource.php?query=<?php echo $_GET['query']."&offset=".$i?>"><?php echo $i ?></a></li>
                      <?php }
                      if(isset($_GET['offset'])){
                        $offset = $_GET['offset'];
                      }
                        ?>
                      <li>
                         <a href="datasource.php?query=<?php echo $_GET['query']."&offset=".get_next($offset,$total_num1,$N)?>" aria-label="Next">
                         <span aria-hidden="true">&raquo;</span>
                      </a>
                     </li>
                
                     </ul>
                     </nav>
                <?php }}?>
          
      <!-- end pagination part -->
	<!--<table class="table table-striped" >-->
         <div class="table-responsive">          
          <table class="table">
            <thead>             
            </thead>
            <tbody>
                <ul>
              		<?php foreach($show_all_items as $item):?>
                  <li>
                     <?php $keys = array_keys($item);?>
                    
                      <h5><a href="<?php echo $item['ref'];?>" target="_blank"><em><span style="text-decoration: underline;"><?php echo $item[$keys[0]];?></span></a></em></h5>
              		   <?php foreach(array_slice($keys,1,sizeof($keys)-4) as $key):?>
                        <?php echo '<em>'.$key.'</em>:'.$item[$key];?>
                     <?php endforeach;?> 
                     <br>
                     From <a href="result.php?query=<?php echo $q;?>&datasource=<?php echo $item['source_ref'];?>"><?php echo $item['source'];?>
                     <br>
                  </li>

                <?php endforeach;?>  
               </ul>     
             </tbody>   
              
   </table>
  			</div>    		  
		 </div>
     <!-- end show result part -->      
               
     </div>
<?php include 'footer.php';?>
       
