<?php
//require_once 'app/init.php';
//require_once 'datatypes.php';
require_once 'repositories_model.php';
require_once 'search_model.php';
require_once 'helper_functions.php';
//echo print_r($_POST);
session_start();

if(isset($_SESSION['filters']) && isset($_GET['offset'])){
   $_POST = $_SESSION['filters'];
}
else{
  $_SESSION['filters']=$_POST ;
}


$q=$_GET['query'];
$datasource=$_GET['datasource'];
//echo print_r($_POST);
$offset = 1;
if (isset($_GET['offset'])){
      $offset = $_GET['offset'];
 }

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
           $search_repository->filter_fields = $repository->decode_filter_fields($_POST);
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
//echo print_r($results);
$facets_filters = get_facets($current_query);

//$aggs_keywords = get_aggs($query)[0];
//$max_score = get_aggs($query)[1];
$total_num = $current_query['hits']['total'];

$headers = $current_repository->headers;
$show_results = $current_repository->show_table($results);
//echo print_r($show_results);
?>
<?php include 'header.php';?>

      <!--<div class="jumbotron">-->
      <div class="col-lg-12">
        <form action="result.php" method='get' autocomplete='off'>
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Search for data through BioCADDIE" name='query'>
          <input type="hidden" name="datasource" value="<?php echo $datasource;?>">
          <div class="input-group-btn">
          <input class="btn btn-default" type='submit' value='Go!'>
          </div><!-- /btn-group -->
       </div><!--input-group-->
       </form>
    </div><!-- /.col-lg-12 -->
   <div class="row">
        <div class="col-md-4" id="leftCol">
               <h4><a href="datasource.php?query=<?php echo $q;?> ">Show results for all</a></h4>
                <ul class="nav nav-stacked" id="sidebar">
                  <?php foreach($all_repositories as $repository):?>
                    <tr><font size="4"><a href="result.php?query=<?php echo $q;?>&datasource=<?php echo $repository->id;?>"> 
                      <?php echo $repository->show_name;?> ( <?php echo $repository->num;?>)</a></font></tr></br><br>           
                  <?php endforeach;?> 
               </ul>
      <!--facets part -->
         <ul class="nav nav-stacked" id="sidebar">
                  <h3> Refined by</h3>
         <form  action="result.php?query=<?php echo $q.'&datasource='.$datasource;?>" method="post" autocomplete='off'>
         <?php foreach($facets_filters as $facets_filter){
              $key = $facets_filter['key'];
               $target = str_replace('.','_',$key);
              $term_array = $facets_filter['term_array'];
              //echo print_r($term_array);?>
              <button id="button" type="button" class="btn btn-primary" data-toggle="collapse" data-target="<?php echo "#".$target ?>">
                <span class="glyphicon glyphicon-collapse-down"></span> <?php echo $key ?>
              </button>
             <div id="<?php echo $target?>" class="collapse.in">
            
                <?php foreach($term_array as $tag){ 
                   $flag = " ";
                   $newString=convert_facets_post($tag['name']);
                  if(array_key_exists($newString, $_POST)){
                    $flag = 'checked';
                   }
                   ?>
                <div class="checkbox">
                      <label><input type="checkbox" name="<?php echo $tag['name']?>" <?php echo $flag;?>> <?php echo $tag['show_name'].' ('.$tag['count'].')'; ?></label>
                </div>
                 <?php }?>
              </div><br>
                <?php } ?>

              <input class="btn btn-default" type='submit' value='submit'>
            </form>
        </ul>
          </div>  
      <!--end facets part -->  

      <!-- pagination part -->
          <div class="col-md-8">

              <?php if (isset($results)) {?>
                  <h4><?php echo show_current_record_number($offset,$total_num,$N)?> of <?php echo $total_num?> results for "<b><?php echo $_GET['query']?>"</b> in <b><?php echo $current_repository->show_name;?></b></h4>
                 <?php if ($total_num/$N>1){?>
                      <nav>
                        <ul class="pagination">
                          <li>
                            <a href="result.php?query=<?php echo $_GET['query'].'&datasource='.$datasource."&offset=".get_previsoue($offset);?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            </a>
                          </li>
                      <?php
                  for($i=1;$i<min(10,$total_num /$N);$i++){
                       ?>
                       <li><a href="result.php?query=<?php echo $_GET['query'].'&datasource='.$datasource."&offset=".$i?>"><?php echo $i ?></a></li>
                      <?php }
                      if(isset($_GET['offset'])){
                        $offset = $_GET['offset'];
                      }
                        ?>
                      <li>
                         <a href="result.php?query=<?php echo $_GET['query'].'&datasource='.$datasource."&offset=".get_next($offset,$query['hits']['total'],$N)?>" aria-label="Next">
                         <span aria-hidden="true">&raquo;</span>
                      </a>
                     </li>
                
                     </ul>
                     </nav>
                <?php }}?>
          
      <!-- end pagination part -->
 <table class="table table-striped">
   <thead>
      <tr>
              <?php foreach($headers as $header):?>
                  <th><?php echo $header;?></th>
              <?php  endforeach; ?>
                  
      </tr>
  </thead>
    <tbody>   <!--need refined-->
   <?php foreach($show_results as $show_line):?>
    <tr>
       <?php foreach($show_line as $show):?>
           <td> <?php echo $show;?></td>
       <?php endforeach;?>
    </tr>
   <?php endforeach;?>


   </tbody>
  </table>
  </div>    
             
     <!-- end show result part -->      
   </div>      <!--end row-->        

   <?php include 'footer.php';?>
