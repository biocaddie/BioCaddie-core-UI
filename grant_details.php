<?php
require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/Model/PubmedGrantService.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';
require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/views/search_panel.php';
require_once dirname(__FILE__) . '/views/grant_details/grant_details.php';
require_once dirname(__FILE__) . '/views/feedback.php';

$pageTitle = "Grant Support";

$service_grant = new PubmedGrantService();
$service = new SearchBuilder();
?>
<?php include dirname(__FILE__) . '/views/header.php'; ?>
<div class="container">   
    <div class="row">
        <div class="col-lg-12">
            <?php echo partialSearchPanel($service); ?>
        </div>
        
        <div class="col-lg-9">

            <?php echo partialGrantDetial($service_grant); ?>
        </div>

        <div class="col-lg-3">
            <?php echo partialFeedback(); ?>
        </div>
    </div>
</div>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>