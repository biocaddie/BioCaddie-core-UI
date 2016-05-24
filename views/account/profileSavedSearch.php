<div id="savedSearch" class="panel panel-primary">
    <div class="panel-heading">
        <strong>Saved Search Query</strong>
    </div>

    <div class="panel-body">
        <table class="table">
            <thead>
            <th>Date</th>
            <th>Type</th>
            <th>Term</th>
            </thead>

            <?php foreach ($result as $row) {
                $href="./search.php?query=". $row['search_term']."&searchtype=". $row['search_type'];
                $href = str_replace('"','',$href);
                ?>
                <tbody>
                <tr>
                    <td><?php echo $row['create_time'];?></td>
                    <td><?php echo $row['search_type'];?></td>
                    <td><a class="hyperlink"
                           href='<?php echo $href?>'><?php echo $row['search_term'] ?></a></td>
                </tr>
                </tbody>

            <?php }?>
        </table>
    </div>
    <div class="panel-footer">
        <a class="hyperlink" href="savedsearch.php">View Saved Search Query >></a>
    </div>
</div>