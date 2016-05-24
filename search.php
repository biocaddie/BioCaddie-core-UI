<?php
require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';

require_once dirname(__FILE__) . '/views/search/search_panel.php';
require_once dirname(__FILE__) . '/views/search/breadcrumb.php';

// Left column
require_once dirname(__FILE__) . '/views/search/repositories.php';
require_once dirname(__FILE__) . '/views/search/datatypes.php';
require_once dirname(__FILE__) . '/views/search/accessibility.php';
require_once dirname(__FILE__) . '/views/feedback.php';


// Middle column
require_once dirname(__FILE__) . '/views/search/results.php';
require_once dirname(__FILE__) . '/views/search/switch_view.php';
require_once dirname(__FILE__) . '/views/search/pagination.php';
require_once dirname(__FILE__) . '/views/search/result_status.php';
require_once dirname(__FILE__) . '/views/search/sorting.php';

// Right column
require_once dirname(__FILE__) . '/views/search/partialActivities.php';
require_once dirname(__FILE__) . '/views/search/synonym.php';
require_once dirname(__FILE__) . '/views/search/search_details.php';

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$searchBuilder = new SearchBuilder();

/*Share search results*/
if (isset($_POST['radio-share'])) {
    $shareType = $_POST['radio-share'];
    $sharedData = "";

    $selectedRows = explode(",", $_POST['selected-rows']);
    $searchResults = $searchBuilder->getSearchResults();

    $newLine = $shareType == "file" ? "\r\n" : "<br />";

    foreach ($selectedRows as $rowIndex) {
        $row = $searchResults[intval($rowIndex)];
        $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;
        foreach ($row as $field) {
            $sharedData .= $field . $newLine;
        }
        $sharedData .= $newLine;
    }

    // File
    if ($shareType == "file") {
        header('Content-Disposition: attachment; filename="biocaddie-share.txt"');
        header('Content-Type: text/plain');
        header('Content-Length: ' . strlen($sharedData));
        header('Connection: close');
        echo $sharedData;
    } // Email
    else {
        require_once dirname(__FILE__) . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

        $from = 'biocaddie.mail@gmail.com';
        $to = $_POST['EmailAddress'];
        $subject = isset($_POST['EmailSubject']) ? ' - ' . $_POST['EmailSubject'] : '';
        $body = '<p>' . $_POST['EmailBody'] . '</p>' . $sharedData;

        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
            ->setUsername('biocaddie.mail@gmail.com')
            ->setPassword('biocaddie4050@');

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance('bioCaddie Data Export' . $subject)
            ->setFrom(array($from => 'bioCaddie'))
            ->setTo(array($to))
            ->setBody($body)
            ->setContentType("text/html");

        $result = $mailer->send($message);
    }
}
?>
<?php include dirname(__FILE__) . '/views/header.php'; ?>

<div class="container">
    <?php echo partialSearchPanel($searchBuilder); ?>
    <?php echo breadcrumb($searchBuilder); ?>

    <div class="row">
        <?php /* ###### Filter Panel ###### */ ?>
        <div class="col-sm-4 col-md-3">
            <?php
            if ($searchBuilder->getSearchType() == 'data') {
                echo partialRepositories($searchBuilder);
                echo partialDatatypes($searchBuilder);

            }
            ?>
            <div id="repo-filter"></div>
            <?php echo partialFeedback(); ?>
        </div>

        <?php /* ###### Search Result Panel ###### */ ?>
        <div id="detailView"></div>
        <div id="summaryView">
            <div class="col-sm-8 col-md-6">

                <?php /* ==== Pagination Panel ==== */ ?>
                <?php if ($searchBuilder->getTotalRows() > 0): ?>
                    <?php echo partialResultsStatus($searchBuilder);?>
                    <?php echo partialSwitch($searchBuilder); ?>
                    <div class="clearfix"></div>
                    <?php echo partialPagination($searchBuilder);?>
                    <div class="clearfix"></div>
                    <div style="margin-bottom: 60px">
                    <?php echo partialSorting($searchBuilder); ?>
                    <?php echo partialAccessibility($searchBuilder);?>

                        </div>
                <?php endif; ?>


                <div class="clearfix"></div>


                <?php /* ==== Search Result List ==== */ ?>
                <?php echo partialResults($searchBuilder);?>

                <hr>
                <?php /* ==== Pagination Panel ==== */ ?>
                <?php if ($searchBuilder->getTotalRows() > 0): ?>
                    <?php echo partialResultsStatus($searchBuilder); ?>
                    <div class="clearfix"></div>
                    <?php echo partialPagination($searchBuilder); ?>
                <?php endif; ?>
                <div class="clearfix"></div>
            </div>

            <?php /* ###### Info Panel ###### */ ?>
            <div class="col-md-3 hidden-sm">
                <?php echo partialActivities(); ?>
                <?php echo partialSynonym($searchBuilder);?>
                <?php echo partialSearchDetails($searchBuilder); ?>
            </div>
        </div>
    </div>
</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/search.js"];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>
