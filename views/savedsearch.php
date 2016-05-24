<div class="container">

    <div class="row" style="margin-bottom:30px;margin-top:50px">
        <button type="submit" class="btn btn-primary" id="btn-delete" disabled="true">Delete Saved Search</button>
    </div>

    <div class="row">
    <div id="savedSearch" class="panel panel-primary">
        <div class="panel-heading">
            <strong>Saved Search</strong>
        </div>

        <div class="panel-body">
            <table class="table">
                <thead>
                <th>Date</th>
                <th>Type</th>
                <th>Term</th>
                </thead>

                <?php
                if(count($result)>0){
                foreach ($result as $row) {
                    $href = "./search.php?query=" . $row['search_term'] . "&searchtype=" . $row['search_type'];
                    $href = str_replace('"', '', $href);
                    ?>
                    <tbody>
                    <tr>
                        <td>
                            <?php
                            echo '<input type="checkbox" value="' . $row['search_id'] . '" class="activityChkbox" name="check_list[]" onClick="EnableDelete(this)" name="check_list[]">';
                            echo " " . $row['create_time']; ?></td>
                        <td><?php echo $row['search_type']; ?></td>
                        <td><a class="hyperlink" href='<?php echo $href ?>'><?php echo $row['search_term'] ?></a></td>
                    </tr>
                    </tbody>
                <?php }
                }?>
            </table>
        </div>
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
                    The search was deleted successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
