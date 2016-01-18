 <div>
      <?php if (isset($results)) {?>
      <h5><?php echo show_current_record_number($offset,$total_num,$N)?> of <?php echo $total_num?> results for "<b><?php echo $_GET['query']?>"</b> in <b><?php echo $current_repository->show_name;?></b></h5>
      
      <?php echo show_pagination($total_num,$offset,'result.php?query='.$_GET["query"].'&datasource='.$datasource."&offset=",$N); ?>

      
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

<?php if(count($show_results)<1):?>

  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> No items found.
<?php endif; ?>

</tbody>
</table>

