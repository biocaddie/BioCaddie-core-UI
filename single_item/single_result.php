<div class = "dataset-info">
  <div class = "repo-logo">
    <a href="<?php echo $repository->source_main_page;?>" target="_blank"><img src="./img/rcsb_logo.png"></img></a>
  </div><!--/.repo-logo-->

  <div class = "data-title">
    <!--title-->
    <h4><?php echo $show_results[$repository->datasource_headers[0]];?></h4>

     <!--id, keyword, description-->
    <table class="table table-default">
      <tbody>
        <?php foreach(array_keys($show_results) as $key):
        if($key==$repository->datasource_headers[0]){
          continue;
        }
        if(strpos($key,'dataItem')!== false){?>
          <tr>
            <td> 
             <b><?php echo $repository->core_fields_show_name[$key].":";?></b>
            </td>
            <td> 
             <?php echo $show_results[$key];?>
            </td>
          </tr>
        <?php }?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div> <!--/.data-title-->

 <div class = "data-other">
  
    <?php if($_GET['sourceid']=='0002'){?>  
    <!--hard code for expand the table for PDB-->
    <table class="table table-striped table-hover dashboard">
     <tbody>  
        <?php foreach(array_keys($show_results) as $key):
        if($key==$repository->datasource_headers[0]){
          continue;
        }
        if(strpos($key,'dataItem')== false){?>
          <?php if(strlen($show_results[$key])==0){?>
          
          <tr class="clickable" data-toggle="collapse">
          <td colspan="4">
            <h4><span class="label label-default data-info-title">
            <i class="glyphicon glyphicon-collapse-down"></i> <?php echo $repository->core_fields_show_name[$key];?>
            </span></h4>
          </td>
          </tr>
        <?php }
        else{ ?>
          <tr class="collapse">
            <td><b> <?php echo $repository->core_fields_show_name[$key];?></b></td>
            <td> <?php echo $show_results[$key];?></td>
          </tr>
        <?php } 
        } // end of if(strpos($key,'dataItem')== false)
        endforeach; ?>
      </tbody>   
    </table>
    </div> <!--/.data-other-->
      <?php } // end of pdb

else{ ?> <!--Other data repositories-->
 
<table class="table table-striped">
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
  </div> <!--/.data-other-->
<?php }?> <!--end of other data repositories-->


<?php
if(count(array_keys($show_results))<=35){
	for($i=0;$i<35-count(array_keys($show_results));$i++){
		echo '<br>';
  }
}
?>
</div> <!--/.dataset-info-->

