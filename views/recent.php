
<form  method="post" id="saveSearchForm">
<div class="container">

    <?php
    if (!isset($_SESSION['name'])) {
        ?>
        <div style="margin-top:40px">
        <h4><span class="label custom-warning"><a href="register.php" class="hyperlink">Register</a> or <a
                    href="login.php" class="hyperlink">Sign in </a>and you will gain the ability to permanently store search queries and records.</span>
        </h4>
            </div>
        <div class="row">
        <div style="margin-bottom:30px;margin-top:50px">
            <button type="submit" class="btn btn-primary" id="btn-save" disabled="true" data-toggle="modal"
                    data-target=".bs-example-modal-sm">Save Search
            </button>
        </div>
    </div>

        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Tips</h4>
                    </div>
                    <div class="modal-body">
                        You need to login to save the search result.
                    </div>
                    <div class="modal-footer">
                       <!-- <button type="submit" class="btn btn-primary" onclick="window.location.href='login.php'">Sign In
                        </button>-->
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
    <div class="row">
        <div style="margin-top:10px">
            <button type="submit" class="btn btn-primary" id="btn-save" disabled="true">Save Search Query</button>
        </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Info</h4>
                    </div>
                    <div class="modal-body">
                        Saved to <a class="hyperlink" href="login.php">MyDataMed</a> successfully!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>


    <div class="row" style="margin-top: 30px">
    <div class="panel panel-success">
        <div class="panel-heading"><strong> Recent
                Activities</strong></div>
        <div class="panel-body">
            <table class="table">
                <thead>
                <th>Date</th>
                <th>Type</th>
                <th>Term</th>
                </thead>
                <?php
                if (count($history) > 0) {
                    $counter = count($history) - 1;
                    ?>

                    <tbody>
                    <?php foreach (array_reverse($history) as $historyItem):
                        $items = explode("|||", $historyItem);
                        $query = $items[0];
                        $searchtype = $items[1];
                        ?>
                        <tr>
                            <td>
                               <?php
                               echo '<input type="checkbox" value="'.$query.'|'.$searchtype.'|'.$date[$counter].'" class="activityChkbox" name="check_list[]" onClick="EnableSubmit(this)" name="check_list[]">';
                               echo " ".$date[$counter]; ?>
                            </td>
                            <td>
                                <?php echo $searchtype ?>
                            </td>
                            <td>
                                <a class="hyperlink"
                                   href="./search.php?query=<?php echo $query ?>&searchtype=<?php echo $searchtype ?>"><?php echo $query ?></a>
                            </td>
                        </tr>
                        <?php $counter--;endforeach; ?>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
    </div>

</form>

