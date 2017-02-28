<?php
require_once dirname(__FILE__) .'/config/config.php';

require_once dirname(__FILE__) . '/search/PubmedGrantService.php';
require_once dirname(__FILE__) . '/views/grant_details/grant_details.php';

//require_once dirname(__FILE__) . '/search/SingleItemDisplayService.php';
///require_once dirname(__FILE__) . '/views/display_item/search_panel.php';
//require_once dirname(__FILE__) . '/views/display_item/breadcrumb.php';
require_once dirname(__FILE__) . '/views/feedback.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';
require_once dirname(__FILE__) . '/views/search/search_panel.php';
require_once dirname(__FILE__) . '/trackactivity.php';
//$service = new SingleItemDisplayService();
$service_grant = new PubmedGrantService();
$service = new SearchBuilder();
?>
<?php include dirname(__FILE__) . '/views/header.php'; ?>
<div class="container">   
    <div class="row">
        <div class="col-lg-12">
            <?php echo partialSearchPanel($service); ?>
            <?php //echo partialBreadcrumbPanel($service); ?>
        </div>
        
        <div class="col-lg-9">

            <?php echo partialGrantDetial($service_grant); ?>
        </div>

        <div class="col-lg-3">
            <div style="margin-bottom: 30px" class="text-center">

                <span class='st_googleplus_large' displayText='Google +'></span>
                <span class='st_facebook_large' displayText='Facebook'></span>
                <span class='st_twitter_large' displayText='Tweet'></span>
                <span class='st_linkedin_large' displayText='LinkedIn'></span>
                <span class='st_pinterest_large' displayText='Pinterest'></span>
                <span class='st_email_large' displayText='Email'></span>
            </div>


            <?php echo partialFeedback(); ?>
        </div>
    </div>
</div>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>