<?php

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) .'/config/datasources.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';
require_once dirname(__FILE__) . '/trackactivity.php';
require_once dirname(__FILE__) .'/search/SubmitDataService.php';
include dirname(__FILE__) . '/views/header.php';
?>


<div class="container">
    <div class="row" style="text-align:center">
        <h3>Get your repository indexed in bioCADDIE</h3>
    </div>

    <div  style="margin-top: 30px">
        <form action='./submit_repository.php' method='post' autocomplete='off' id="search-form"  enctype="multipart/form-data">
            <?php include dirname(__FILE__) . '/views/submit/submit_repository.php'; ?>
        </form>


    </div>

</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/submit.js"];
?>


<?php include dirname(__FILE__) . '/views/footer.php'; ?>

