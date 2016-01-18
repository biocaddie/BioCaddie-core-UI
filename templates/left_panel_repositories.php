<?php
//left column contents
function show_repository_link_panel($all_repositories,$query,$id,$data_index){
  $data_index=explode(',',$data_index);?>
  <ul class="nav-stacked" id="sidebar" style="margin-left: -2em;">
   <?php foreach($all_repositories as $repository):
   $check='';
   
   if($id==$repository->id){
    $check='<i class="fa fa-check-circle-o"></i>';
  }
  else{
    $check='<i class="fa fa-circle-o"></i>';
  }	 
         //echo print_r($data_index);
  foreach($data_index as $datatype){
    if($datatype==$repository->index){
      $check='<i class="fa fa-check-circle-o"></i>';
      break;
    }
  }           
  ?>
  <li style='list-style-type:none'>
    <a href="result.php?query=<?php echo $query;?>&datasource=<?php echo $repository->id;?>"> 
      <?php echo $check;?>
      <?php echo $repository->show_name;?> 
      (<?php echo $repository->num;?>)
    </a>
  </li>           
 <?php endforeach;?> 
 <?php if(strlen($id)>0){
   $check1='<i class="fa fa-circle-o"></i>';?>
   <li style='list-style-type:none'>
      <a href="datasource.php?query=<?php echo $query;?>"> <?php echo $check1;?> ALL </a>
    </li>
   <?php }
   
   ?>
   
 </ul>
 <?php }
 ?>
