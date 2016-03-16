<?php
require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';
require_once dirname(__FILE__) . '/views/search/search_panel.php';
require_once dirname(__FILE__) . '/trackactivity.php';
?>
<?php include dirname(__FILE__) . '/views/header.php'; ?>

    <div class="container">

        <div class="row">
            <h3>Synonyms of "<?php echo $_SESSION['query']; ?>"</h3>
        </div>

        <div class="panel-body">
            <ol class="no-disk">
                <?php foreach ($_SESSION['synonym'] as $item):
                    ?>
                    <li>
                        <span><?php echo $item; ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
<?php include dirname(__FILE__) . '/views/footer.php'; ?>