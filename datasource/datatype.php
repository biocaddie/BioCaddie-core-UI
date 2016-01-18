<div class="panel panel-default">
  <div class="panel-heading"><h4><span class="glyphicon glyphicon-chevron-up"></span> Data Type</h4></div>
  <div class="panel-body">
   <ul class="nav nav-stacked" id="sidebar">

    <form id="datatypes_form" action=<?php echo $href;?>  method="post" autocomplete='off'>
      <div id="datatypes" class="collapse.in">

        <?php foreach($datatypes as $tag){ 

          $flag = " ";
          $newtag = str_replace(' ','_',$tag);
          if(array_key_exists($newtag, $_POST)){
            $flag = 'checked';
          }
          ?>
          <div class="checkbox">
           <label><input type="checkbox" name="<?php echo $tag;?>" 
            <?php echo $flag;?> onchange="document.getElementById('datatypes_form').submit()"> 
            <?php echo ucfirst($tag).' ('.$datatypes_num[$tag].')'; ?>
          </label>
         </div>
         <?php }?>
       </div>

       <br>
     </form>

   </ul>
 </div>
</div>