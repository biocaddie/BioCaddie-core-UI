<?php
require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';


$query='';
$expanquery='';

if(isset($_GET['q'])){
    $query = $_GET['q'];
    $expanquery = $_SESSION['synonym'];
}

$pageTitle = $query;
?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>



    <div class="container">
        <?php include dirname(__FILE__) . '/views/breadcrumb.php'; ?>
        <div class="row">
            <h3>Synonyms of "<?php echo htmlspecialchars($query); ?>"</h3>
        </div>

        <div class="panel-body">
            <ol class="no-disk">
                <?php foreach ($expanquery as $item):
                    ?>
                    <li>
                        <span><?php echo $item; ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
<?php include dirname(__FILE__) . '/views/footer.php'; ?>
