<?php
require_once dirname(__FILE__) .'/config/config.php';
include dirname(__FILE__) . '/views/header.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';
require_once dirname(__FILE__) . '/trackactivity.php';
?>
<?php include dirname(__FILE__) . '/views/breadcrumb.php'; ?>




<div class="container">
    <div class="row">
        <h3>DataMed Advanced Search Builder</h3>
    </div>

    <div  style="margin-top: 30px">
        <form action='./search.php' method='get' autocomplete='off' id="search-form">
            <?php include dirname(__FILE__) . '/views/advanced/builder.php'; ?>
        </form>
    </div>

</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/advanced.js"];
?>


<?php include dirname(__FILE__) . '/views/footer.php'; ?>

