<?php
require_once dirname(__FILE__) .'/config/config.php';

require_once dirname(__FILE__) . '/search/SearchRepositoryBuilder.php';
require_once dirname(__FILE__) . '/views/search_repository/search_panel.php';
require_once dirname(__FILE__) . '/views/search_repository/repositories.php';
require_once dirname(__FILE__) . '/views/search_repository/filters.php';
require_once dirname(__FILE__) . '/views/search_repository/result_status.php';
require_once dirname(__FILE__) . '/views/search_repository/pagination.php';
require_once dirname(__FILE__) . '/views/search_repository/sorting.php';
require_once dirname(__FILE__) . '/views/search_repository/results.php';
require_once dirname(__FILE__) . '/views/search_repository/pilot_projects.php';
require_once dirname(__FILE__) . '/views/search_repository/switch_view.php';
require_once dirname(__FILE__) . '/views/feedback.php';
$searchBuilder = new SearchRepositoryBuilder();

if (isset($_POST['radio-share'])) {
    $shareType = $_POST['radio-share'];
    $sharedData = "";
    
    $selectedRows = explode(",", $_POST['selected-rows']);
    $searchResults = $searchBuilder->getSearchResults();
    
    $newLine = $shareType == "file" ? "\n" : "<br />";
    
    $headers = $searchBuilder->getSearchHeaders();
    
    foreach ($selectedRows as $rowIndex) {
        $row = $searchResults[intval($rowIndex)];
        $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;
        $i = 0;
        foreach ($row as $field) {
            $sharedData .= $headers[$i] . ": " . strip_tags($field) . $newLine;
            $i++;
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
    }
    // Email
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
    <?php /* Search Panel */ ?>
    <?php echo partialSearchPanel($searchBuilder); ?>

    <div class="row">
        <?php /* ###### Filter Panel ###### */ ?>
        <div class="col-sm-4 col-md-3">
            <?php partialRepositories($searchBuilder); ?>
            <?php partialFilters($searchBuilder); ?>
            <?php partialFeedback(); ?>
        </div>

        <?php /* ###### Search Result Panel ###### */ ?>
        <div class="col-sm-8 col-md-9">
            <?php /* ==== Pagination Panel ==== */ ?>
            <?php if ($searchBuilder->getTotalRows() > 0): ?>
                <?php partialResultsStatus($searchBuilder); ?>
                <?php partialPilotProjects($searchBuilder); ?>
                <div class="clearfix"></div>
                <?php partialPagination($searchBuilder); ?>
                <?php partialSwitch($searchBuilder); ?>
                <?php partialSorting($searchBuilder); ?>
            <?php endif; ?>

            <div class="clearfix"></div>
            <?php /* ==== Search Result List ==== */ ?>
            <?php partialResults($searchBuilder); ?>


        </div>
    </div>
</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/searchrepo.js"];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>