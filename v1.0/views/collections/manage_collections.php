<div class="container">
    <div class="row" style="margin-bottom:30px;margin-top:50px">
        <button type="submit" class="btn btn-primary" id="btn-delete-collection" disabled="true">Delete Collection</button>
    </div>

    <div class="row">
        <div id="collections" class="panel panel-primary">
            <div class="panel-heading">
                <strong> Collections </strong>
            </div>

            <div class="panel-body">
                <table class="table">
                    <thead>
                    <th>Name</th>
                    <th>Creation Date</th>
                    <th>Settings</th>
                    </thead>

                    <?php foreach ($result as $row) {
                        ?>
                        <tbody>
                        <tr>
                            <td>
                               <?php
                                echo '<input type="checkbox" value="' . $row['collection_id'] . '" class="activityChkbox" name="check_list[]" onClick="EnableDelete(this)" name="check_list[]">';
                               ?>
                                <a href="manage_collections.php?name=<?php echo $row['collection_name']?>" class="hyperlink">
                                    <?php
                                echo " " . $row['collection_name']; ?></a></td>
                            <td><?php echo $row['create_time']; ?></td>
                            <td><a class="hyperlink" href='#'><?php echo $row['settings'] ?></a></td>
                        </tr>
                        </tbody>
                    <?php } ?>
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
                    The collection was deleted successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
