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
        <h3>Get your dataset indexed in bioCaddie</h3>
    </div>

    <div  style="margin-top: 30px">
        <form action='./submit_data.php' method='post' autocomplete='off' id="search-form">
            <?php include dirname(__FILE__) . '/views/submit/submit.php'; ?>
        </form>
    </div>

</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/submit.js"];
?>


<?php include dirname(__FILE__) . '/views/footer.php'; ?>

