<div id="collections" class="panel panel-primary">
    <div class="panel-heading"><strong><span class="glyphicon glyphicon-chevron-up"></span>Collections</strong></div>
    <div class="panel-body">
        <table class="table">
            <thead>
            <th>Collection Name</th>
            <th>Settings/Sharing</th>
            <th>Create Time</th>
            </thead>

            <?php foreach ($collectionsList as $collection) {
                ?>
                <tbody>
                <tr>
                    <td><a class="hyperlink" href="manage_collections.php?name=<?php echo $collection['collection_name'];?>"><?php echo $collection['collection_name'];?></td>
                    <td><?php echo $collection['settings'];?></td>
                    <td><?php echo $collection['create_time'];?></td>

                </tr>
                </tbody>

            <?php }?>

        </table>
    </div>

    <div class="panel-footer">
        <a class="hyperlink" href="manage_collections.php">Manage Collections >></a>
    </div>
</div>
