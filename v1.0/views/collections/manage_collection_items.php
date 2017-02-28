<div class="container">
    <div class="row" style="margin-bottom:30px;margin-top:50px">
        <button type="submit" class="btn btn-primary" id="btn-delete-items" disabled="true">Delete Dataset</button>
    </div>

    <div class="row">
        <div id="savedSearch" class="panel panel-primary">
            <div class="panel-heading">
                <strong> Dataset in Collection <?php echo $_GET['name']?></strong>
            </div>

            <div class="panel-body">
                <table class="table">
                    <thead>
                    <th class="col-sm-3">Title</th>
                    <th class="col-sm-5">Description</th>
                    <th class="col-sm-1">Repository</th>
                    <th class="col-sm-1">Create Time</th>
                    </thead>

                    <?php foreach ($result as $row) {
                        $prefix_title = 'title:';
                        $title = $row['dataset_title'];
                        if (substr($title, 0, strlen($prefix_title)) == $prefix_title) {
                            $title = substr($title, strlen($prefix_title));
                        }

                        $prefix_desc = 'description:';
                        $desc = $row['dataset_description'];
                        if (substr($desc, 0, strlen($prefix_desc)) == $prefix_desc) {
                            $desc = substr($desc, strlen($prefix_desc));
                        }

                        ?>
                        <tbody>
                        <tr>
                            <td>
                                <?php
                                    echo '<input type="checkbox" value="' . $row['collection_item_id'] . '" class="activityChkbox" name="check_list[]" onClick="EnableDeleteItem(this)" name="check_list[]">';?>
                                <a href="<?php echo $row['dataset_url'] ?>" class="hyperlink">
                                    <?php
                                    echo " " . $title; ?></a></td>
                            <td><?php echo $desc; ?></td>
                            <td><?php echo $row['repository'] ?></td>
                            <td><?php echo $row['create_time'] ?></td>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
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
