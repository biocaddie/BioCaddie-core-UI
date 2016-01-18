<?php
require_once 'config.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';
require_once 'pagination.php';
require_once 'left_panel_repositories.php';
session_start();


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
<?php include 'header.php';?>
     <br>
      <!--<div class="jumbotron">-->
      <div  class="col-lg-12 background">
        <br>
        <form action="result.php" method='get' autocomplete='off'>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for data through BioCADDIE" name='query' value="<?php echo $q;?>">
          <input type="hidden" name="datasource" value="<?php echo $datasource;?>">
          <div class="input-group-btn">
          <input class="btn btn-default" type='submit' value='Go!'>
          </div><!-- /btn-group -->
       </div><!--input-group-->
       </form>
       <br>
    </div><!-- /.col-lg-12 -->
   <div class="row">
        <div class="col-md-3 background_gray" id="leftCol">
                <h4> Repository</h4>
               <?php echo show_repository_link_panel($all_repositories,$q,$datasource,'');?>
                
      <!--facets part -->
     
         <ul class="nav nav-stacked background_gray" id="sidebar">
                  <h4> Refined by</h4>
         <form  id="facets"  action="result.php?query=<?php echo $q.'&datasource='.$datasource;?>" method="post" autocomplete='off'>
         <?php foreach($facets_filters as $facets_filter){
              $key = $facets_filter['key'];
              $key_show = $current_repository->facets_show_name[$key];
               $target = str_replace('.','_',$key);
              $term_array = $facets_filter['term_array'];
              //echo print_r($term_array);?>
              <button id="button" type="button" class="btn btn-primary" data-toggle="collapse" data-target="<?php echo "#".$target ?>">
                <span class="glyphicon glyphicon-collapse-down"></span> <?php echo $key_show ?>
              </button>
             <div id="<?php echo $target?>" class="collapse.in">


                 <!--from here-->
                <?php foreach(array_slice($term_array,0) as $tag){ 
                  
                   $flag = " ";
                   $newString=convert_facets_post($tag['name']);
                  if(array_key_exists($newString, $_POST)){
                    $flag = 'checked';
                   }
                   ?>
                
                 <div class="checkbox">
                      <label><input type="checkbox" name="<?php echo $tag['name']?>" <?php echo $flag;?> onchange="document.getElementById('facets').submit()"> <?php echo $tag['show_name'].' ('.$tag['count'].')'; ?></label>
                       <!--<label><input type="checkbox" name="<?php echo $tag['name']?>" <?php echo $flag;?> > <?php echo $tag['show_name'].' ('.$tag['count'].')'; ?></label>-->
                </div>
                

                 <?php }?> 
      

              </div><br>
                <?php } ?>
                <!--<input class="btn btn-primary" type='submit' value='Submit'>-->
                 
            </form>
        </ul>
        <br>

          </div>  
          <br>

      <!--end facets part -->  
      
      <!-- pagination part -->
          <div class="col-md-9">
             <div style="float:left;">
              <?php if (isset($results)) {?>
                  <h4><?php echo show_current_record_number($offset,$total_num,$N)?> of <?php echo $total_num?> results for "<b><?php echo $_GET['query']?>"</b> in <b><?php echo $current_repository->show_name;?></b></h4>
    
                 <?php echo show_pagination($total_num,$offset,'result.php?query='.$_GET["query"].'&datasource='.$datasource."&offset=",$N); ?>

              
                <?php }?>
             </div>
             <div style="float:right;">
                  <?php if($datasource=='0002'){?>
                  
                  <a href="http://129.106.31.108/pdb_v2/?q=<?php echo $_GET["query"];?>" class="btn btn-info btn-primary" target='_blank'>Explore in iSEE-DELVE</a>
                  <?php }?>
            </div>
      <!-- end pagination part -->
 <table class="table table-striped">
   <thead>
      <tr>
              <?php foreach($headers as $header):?>
                  <th><?php echo $header;?></th>
              <?php  endforeach; ?>
                  
      </tr>
  </thead>
    <tbody>  
   <?php foreach($show_results as $show_line):?>
    <tr>
       <?php foreach($show_line as $show):?>
           <td> 
            <?php echo $show;?>
          </td>
          
       <?php endforeach;?>
    </tr>
   <?php endforeach;?>

   <?php if(count($show_results)<10)?>
   </tbody>
  </table>
  </div>    
             
     <!-- end show result part -->      
   </div>      <!--end row-->        

   <?php include 'footer.php';?>
