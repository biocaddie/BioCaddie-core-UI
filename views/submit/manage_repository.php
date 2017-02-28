<?php

$id = $_SESSION['email'];
$flag = check_manager_email($objDBController, $id);
if (!$flag) {
    echo '<h4 style="text-align:center">Plese <a href="login.php" class="hyperlink">login</a> as manager</h4>';
} else {

    $selectItems = 'all';
    if (isset($_GET['show'])) {
        $selectItems = $_GET['show'];
    }

    $objDBController = new DBController();
    $dbconn = $objDBController->getConn();
    $show_results = show_submitted_repository($objDBController, $selectItems);

    if (isset($_POST['submit'])) {
        change_review_to_db($dbconn, $_POST);
    }

    ?>
    <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            Show <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="manage_submit_repository.php?show=all">All submission</a></li>
            <li><a href="manage_submit_repository.php?show=reviewed">Reviewed</a></li>
            <li><a href="manage_submit_repository.php?show=notreviewed">Not reviewed</a></li>
        </ul>
        <br>
    </div>
    <?php foreach ($show_results as $result): ?>
        <?php
        $select1 = "";
        $select2 = "";

        if (strlen(@$_POST['review_' . $result['ID']]) > 0) {
            $status = $_POST['review_' . $result['ID']];
            if ($status == 'Yes') {
                $select2 = 'selected';
            }
        }


        ?>

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-info">
                <div class="panel-heading" role="tab" id="heading-resource">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion"
                           href="#collapse-resource<?php echo $result['ID']; ?>" aria-expanded="true"
                           aria-controls="collapse-resource<?php echo $result['ID']; ?>">
                            <i class="fa fa-chevron-up"></i>
                            <?php echo $result['ID']; ?>.<?php echo $result['datarepo_name']; ?>
                        </a>
                    <span style="float:right">Review status:
                        <?php if (strlen($result['reviewed']) == 0): ?>
                            <?php if (@$_POST['review_' . $result['ID']] == 'Yes'): ?>
                                <?php echo $_POST['review_' . $result['ID']]; ?>

                            <?php else: ?>
                                <select class="btn btn-default btn-xs" name="review_<?php echo $result['ID']; ?>">
                                    <option <?php echo $select1; ?> value="None">None</option>
                                    <option <?php echo $select2; ?> value="Yes">Yes</option>

                                </select>

                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo $result['reviewed']; ?>
                        <?php endif; ?>
                   </span>
                    </h4>

                </div>
                <div id="collapse-resource<?php echo $result['ID']; ?>" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel panel-info">
                        <div id="collapse-resource" class="panel-collapse collapse in" role="tabpanel">
                            <table class="table table-striped">
                                <tbody>
                                <?php foreach (array_keys($result) as $key) { ?>
                                    <?php if ($key == 'ID' || $key == 'reviewed') {
                                        continue;
                                    } ?>
                                    <tr>
                                        <td style="width: 20%;"><?php echo $key; ?></td>
                                        <td> <?php echo $result[$key]; ?></td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="panel-footer" style="height: 60px">
        <div>
            <button type="submit" class="btn btn-warning pull-right" id="btn-submit" name="submit">Save</button>
        </div>
    </div><!--/.panel-footer-->

<?php } ?>

