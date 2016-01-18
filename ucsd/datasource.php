<?php
require_once 'config.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';
require_once 'pagination.php';
require_once 'left_panel_repositories.php';
//echo print_r($_POST);

//session_start();
/*$datatype_index = ['protein'=>'pdb2',
                   'phenotype'=>'phenodisco',
                   'gene_expression'=>'geo',
                   'gene expression'=>'geo'];

$es_index = 'pdb2,geo,phenodisco';

$datatypes = ['protein','phenotype','gene expression'];*/

//phpinfo() ;

session_start();
//echo 'session'.print_r($_SESSION).'<br>';
//echo 'post'.print_r($_POST).'<br>';
//echo '<br>';
if(isset($_SESSION['filters']) && isset($_GET['offset'])){
  //echo 'yes';
   //$_POST = $_SESSION['filters'];
   $_POST = get_rid_of_sessionname($_SESSION['filters']);
}
else{//} if(!isset($_SESSION['filters'])){
  //echo 'no<br>';
  //$_SESSION['filters']=$_POST ;
  $_SESSION['filters']=get_rid_of_sessionname($_POST) ;
}
//echo 'after';
//echo print_r($_POST);
//echo '<br>';
//echo print_r($_SESSION);
$new_post = get_rid_of_sessionname($_POST);
if(count(array_keys($new_post))>0){
  $es_index = '';
  foreach(array_keys($new_post) as $key){
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


<?php include 'header.php';?>
        <br>
      <div class="col-lg-12 background">
        <br>
        <form action='datasource.php' method='get' autocomplete='off'>
         <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for data through BioCADDIE"  name='query' value="<?php echo $q;?>">
                <div class="input-group-btn">
                <input class="btn btn-default" type='submit' value='Go!'>
              </div><!-- /btn-group -->
        </div><!--input-group-->
       </form>
       <br>
      </div><!-- /.col-lg-12 -->
      
     
     
     <div class="row">
  			<div class="col-md-3 background_gray" id="leftCol">
               <h4> Show results for</h4>
               <!--<?php echo show_repository_link_panel($all_repositories,$q,'');?>-->

      <!--facets part -->
      
				 <ul class="nav nav-stacked background_gray" id="sidebar">
              
              <form id="datatypes_form" action=<?php echo $href;?>  method="post" autocomplete='off'>
                  <!--<button id="button" type="button" class="btn btn-primary" data-toggle="collapse" data-target="#datatypes">
                      <span class="glyphicon glyphicon-collapse-down"></span> DataTypes
                 </button>-->
               <div id="datatypes" class="collapse.in">
        
                  <?php foreach($datatypes as $tag){ 
                  
                      $flag = " ";
                      $newtag = str_replace(' ','_',$tag);
                      if(array_key_exists($newtag, $_POST)){
                          $flag = 'checked';
                      }
                  ?>
                   <div class="checkbox">
                       <label><input type="checkbox" name="<?php echo $tag;?>" <?php echo $flag;?> onchange="document.getElementById('datatypes_form').submit()"> <?php echo $tag.' ('.$datatypes_num[$tag].')'; ?></label>
                  </div>
                  <?php }?>
             </div>

             <br>
				      <!--<input class="btn btn-default" type='submit' value='submit'>-->
				    </form>

        </ul>
        <h4> Repository</h4>
      <?php echo show_repository_link_panel($all_repositories,$q,'',$es_index);?>
      	</div>  
      
      		<div class="col-md-9">
                
        			
     
      <!--show result part -->
      <!-- pagination part -->
            <?php if($total_num1>0){?>
              <?php if (isset($show_all_items)) {?>
                  <h4>Displaying <?php echo show_current_record_number($offset,$total_num1,$N)?> of <?php echo $total_num1?> results for "<b><?php echo $q;?>"</b></h4>

                  <?php echo show_pagination($total_num1,$offset,'datasource.php?query='.$_GET["query"]."&offset=",$N); ?>

              <?php } }?>
      <!-- end pagination part -->
	<!--<table class="table table-striped" >-->
         <div class="table-responsive">          
          <table class="table">
            <thead>             
            </thead>
            <tbody>
                <ol start="<?php echo ($offset-1)*$N+1;?>">
              		<?php foreach($show_all_items as $item):?>
                  <li>
                     <?php $keys = array_keys($item);?>
                    
                      <!--<h5><a href="<?php echo $item['ref'];?>"><em><span style="text-decoration: underline;"><?php echo $item[$keys[0]];?></span></a></em></h5>-->
                      <h4><a href="<?php echo $item['ref'];?>"><?php echo $item[$keys[0]];?></a></h4>
              		   <?php foreach(array_slice($keys,1,sizeof($keys)-4) as $key):?>
                        <?php echo '<em>'.$key.'</em>:'.$item[$key];?>
                     <?php endforeach;?> 
                     <br>
                     From <a href="result.php?query=<?php echo $q;?>&datasource=<?php echo $item['source_ref'];?>"><?php echo $item['source'];?></a>
                     <br>
                  </li>

                <?php endforeach;?>  
               </ol>     
             </tbody>   
              
        </table>
  			</div>    		  
		 </div>
    
     <!-- end show result part -->      
               
     </div>

<?php include 'footer.php';?>
       
