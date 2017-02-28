<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>DataMed | bioCADDIE DDI</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Styles -->
    <link href="vendor/jquery/jquery-ui.css" rel="stylesheet"/>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="vendor/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />


    <link href="./vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />


    <link href="./css/theme.css" rel="stylesheet">
    <link href="./css/expand.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="vendor/jquery/jquery-1.11.3.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!--[if lt IE 9]>
    <script src="./js/ie8-responsive-file-warning.js"></script>
    <![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="./js/html5shiv.min.js"></script>
    <script src="./js/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="./js/js.cookie.js"></script>

    <!---For sign in using google account-->
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="829717013649-0j7el8re8ka2d5tgqm87c5msek06kgmu.apps.googleusercontent.com">
    <script src="./js/platform.js" async defer></script>
    <script type="text/javascript" src="./js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="./js/global.scripts.js"></script>


    <!--Google Analytics-->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-74660564-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<?php

require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/Model/ConstructSearchView.php';

require_once dirname(__FILE__) . '/views/search/repositories.php';
require_once dirname(__FILE__) . '/views/search/datatypes.php';
require_once dirname(__FILE__) . '/views/search/accessibility.php';
require_once dirname(__FILE__) . '/views/search/authorization.php';
require_once dirname(__FILE__) . '/views/feedback.php';

$searchBuilder = new SearchBuilder();
$searchBuilder->searchAllRepo();
$searchView = new ConstructSearchView($searchBuilder);
?>

<body>
<?php
if ($searchBuilder->getSearchType() == 'data') {
    echo partialDatatypes($searchView);
    echo partialRepositories($searchView);
    echo partialAccessibility($searchView);
    echo partialAuthorization($searchView);
}
?>
<div id="repo-filter"></div>
<?php echo partialFeedback(); ?>
</body>
</html>
