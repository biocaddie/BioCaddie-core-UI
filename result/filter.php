<div class="panel panel-default">
 <div class="panel-heading"><h4><span class="glyphicon glyphicon-chevron-up"></span>    Filter<h4></div>
 <div class="panel-body">
   <ul class="nav nav-stacked" id="sidebar">
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
                  <label><input type="checkbox" name="<?php echo $tag['name']?>" 
                    <?php echo $flag;?> onchange="document.getElementById('facets').submit()"> 
                    <?php echo ucfirst($tag['show_name']).' ('.$tag['count'].')'; ?>
                  </label>
                </div>
              
                <?php }?> 
                
              </div><br>
              <?php } ?>
              
            </form>
          </ul>
          
        </div>
      </div>