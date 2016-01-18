<!--show result part -->
<!-- pagination part -->
<div class="row">
  <?php if($total_num1>0){?>
  <?php if (isset($show_all_items)) {?>

  <p>Displaying <?php echo show_current_record_number($offset,$total_num1,$N)?> 
    of <?php echo $total_num1?> results for "<b style="color:#009344"><?php echo $q;?>"</b></p>


  <?php echo show_pagination($total_num1,$offset,'datasource.php?query='.$_REQUEST["query"]."&offset=",$N); ?>

  <?php } }?>
</div>
<!-- end pagination part -->

<!--<table class="table table-striped" >-->
<div class="table-responsive">          
  <table class="table table-striped">
    <tbody>
      <?php foreach($show_all_items as $item):?>
        <tr>  
           <!--<ol start="<?php echo ($offset-1)*$N+1;?>">-->
          <td>
             <?php $keys = array_keys($item);?>
             <h4><a href="<?php echo $item['ref'];?>" style="color:black;font-size:16px;">
              <?php echo $item[$keys[0]];?></a></h4><br>
             <?php foreach(array_slice($keys,1,sizeof($keys)-4) as $key):?>
             <?php echo '<em >'.$key.'</em>:'.$item[$key];?>
             <br>
           <?php endforeach;?> 
           From <a href="result.php?query=<?php echo $q;?>&datasource=<?php echo $item['source_ref'];?>"><?php echo $item['source'];?></a>
           <br>
       </td>
     </tr>
 <?php endforeach;?>   
   
</tbody>   

</table>
</div>  