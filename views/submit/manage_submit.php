<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 4/25/16
 * Time: 4:35 PM
 */

$id=$_SESSION['email'];
$flag = check_manager_email($objDBController,$id);
if (!$flag){
    echo '<h4 style="text-align:center">Plese <a href="login.php" class="hyperlink">login</a> as manager</h4>';
}
else{

$selectItems = 'all';
if(isset($_GET['show'])){
    $selectItems=$_GET['show'];
}
//var_dump($selectItems);
$objDBController = new DBController_submit();
$dbconn=$objDBController->getConn();
$show_results = show_submitted($objDBController,$selectItems);

//var_dump($show_results);
//var_dump($_POST);
if(isset($_POST['submit'])){
    change_approve_to_db($dbconn,$_POST);
}

?>
<div class="btn-group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Show <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a href="manage_submit_data.php?show=all">All submission</a></li>
        <li><a href="manage_submit_data.php?show=approved">Approved</a></li>
        <li><a href="manage_submit_data.php?show=disapproved">Disapproved</a></li>
        <li><a href="manage_submit_data.php?show=notreviewed">Not reviewed</a></li>
    </ul>
    <br>
</div>

<?php foreach($show_results as $result):?>
    <?php
    $select1="";
    $select2="";
    $select3="";
    if(strlen($_POST['approve_'.$result['ID']])>0){
        $status = $_POST['approve_'.$result['ID']];
        if($status=='Yes'){
            $select2='selected';
        }
        if($status=='No'){
            $select3='selected';

        }
    }
        ?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="heading-resource">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-resource<?php echo $result['ID'];?>" aria-expanded="true" aria-controls="collapse-resource<?php echo $result['ID'];?>">
                        <i class="fa fa-chevron-up"></i>
                        <?php echo $result['ID'];?>.        <?php echo  $result['dataset_title'];?>
                    </a>

                    <span style="float:right"> Approve status:
                    <?php if(strlen($result['approve_status'])==0 ):?>
                        <?php if($_POST['approve_'.$result['ID']]=='Yes' ||$_POST['approve_'.$result['ID']]=='No' ):?>
                            <?php echo $_POST['approve_'.$result['ID']];?>

                        <?php else:?>
                            <select class="btn btn-default" name="approve_<?php echo $result['ID'];?>">
                                <option <?php echo $select1;?> value="None">None</option>
                                <option <?php echo $select2;?> value="Yes">Yes</option>
                                <option <?php echo $select3;?> value="No">No</option>
                            </select>

                        <?php endif;?>
                   <?php else:?>
                        <?php echo $result['approve_status'];?>
                   <?php endif;?>
                        </span>
                </h4>

            </div>
            <div id="collapse-resource<?php echo $result['ID'];?>" class="panel-collapse collapse" role="tabpanel" >
                <div class="panel panel-info">
                    <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel" >
                        <table class="table table-striped">
                            <tbody>
                            <?php foreach(array_keys($result) as $key){?>
                                    <?php if($key=='ID' || $key=='approve_status'){
                                            continue;
                                    }?>
                                    <tr>
                                        <td style="width: 20%;"><?php echo $key;?></td>
                                            <td> <?php echo $result[$key];?></td>
                                    </tr>

                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
        </div>
<?php endforeach;?>



<div class="panel-footer" style="height: 60px">
    <div>
        <button type="submit" class="btn btn-warning pull-right" id="btn-submit" name="submit">Save</button>
    </div>
</div><!--/.panel-footer-->


<?php }?>
