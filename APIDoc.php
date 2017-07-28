<?php ;
$base= dirname($_SERVER['SCRIPT_NAME']);
if ($base=='/'){
    //pass;
}
else{
    $base=$base.'/';
}?>


<!DOCTYPE html>
<html lang="en">
<head>

    <title><?php echo isset($pageTitle)&& $pageTitle!=" " ? $pageTitle . " | " : "DataMed | "; ?> bioCADDIE Data Discovery Index</title>
    <meta name="description" content="DataMed is a prototype biomedical data search engine. Its goal is to discover data sets across data repositories or data aggregators. In the future it will allow searching outside these boundaries. DataMed supports the NIH-endorsed FAIR principles of Findability, Accessibility, Interoperability and Reusability of datasets with current functionality assisting in finding datasets and providing access information about them.">
    <meta name="author" content="bioCaddie Core Development Team.">
    <meta name="keywords" content="DataMed,
                                        biomedical data search engine,
                                        biomedical data,
                                        Healthcare data,
                                        data discovery index,
                                        bioCADDIE,
                                        biomedical and healthcare data discovery index ecosystem,
                                        NIH,
                                        National Institutes of Health,
                                        BD2K,
                                        Big Data to Knowledge,
                                        data repository,
                                        data aggregator,
                                        FAIR,
                                        Findability,
                                        Accessibility,
                                        Interoperability,
                                        Reusability,
                                        dataset,
                                        data set,
                                        metadata,
                                        DATS,
                                        Data Tag Suite,
                                        citation,
                                        publication
                                        ">
    <!--<base href="/biocaddie-ui/" />-->
    <base href="<?php echo $base;?>" />
    <link href="./img/favicon.png" rel="icon" >

    <!-- Styles -->
    <link href="./vendor/jquery/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./vendor/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />
    <!--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">-->
    <link href="./vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!--<link href="./css/theme.css" rel="stylesheet">-->
    <link href="./css/expand.css" rel="stylesheet">


    <!-- Scripts -->

    <!-- <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>-->
    <script src="https://d3js.org/d3-format.v1.min.js"></script>

    <script src="./vendor/jquery/jquery-1.11.3.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js" " crossorigin="anonymous"></script>


    <script src="./vendor/d3/d3.v3.min.js"></script>
    <script src="./js/jsapi.js"></script>

    <!--[if lt IE 9]>
    <script src="./js/ie8-responsive-file-warning.js"></script>
    <![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="./js/html5shiv.min.js"></script>
    <script src="./js/respond.min.js"></script>
    <![endif]-->

    <script src="./js/js.cookie.js"></script>

    <!---For sign in using Google account-->
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="829717013649-0j7el8re8ka2d5tgqm87c5msek06kgmu.apps.googleusercontent.com">
    <script src="./js/platform.js" async defer></script>
    <script type="text/javascript" src="./js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="./js/global.scripts.js"></script>

    <script src="./vendor/d3/d3.layout.cloud.js"></script>
    <script src="./vendor/d3/d3-tip.js"></script>


    <!--Google Analytics-->
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-80500704-1', 'auto');
        ga('send', 'pageview');
    </script>

    <!--Usability Tracking-->
    <script src="./js/client.min.js"></script>


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="vendor/swagger/swagger-ui.css" >
    <link href="./img/favicon.png" rel="icon" >
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body {
            margin:0;
            background: #fafafa;
        }
        .topbar {
            display:none;
        }
    </style>


    <?php if (strpos($_SERVER['REQUEST_URI'], "display-item.php?repository") !==false) {include "schemaorg.php";} ?>
</head>




<body>

<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="position:absolute;width:0;height:0">
  <defs>
    <symbol viewBox="0 0 20 20" id="unlocked">
          <path d="M15.8 8H14V5.6C14 2.703 12.665 1 10 1 7.334 1 6 2.703 6 5.6V6h2v-.801C8 3.754 8.797 3 10 3c1.203 0 2 .754 2 2.199V8H4c-.553 0-1 .646-1 1.199V17c0 .549.428 1.139.951 1.307l1.197.387C5.672 18.861 6.55 19 7.1 19h5.8c.549 0 1.428-.139 1.951-.307l1.196-.387c.524-.167.953-.757.953-1.306V9.199C17 8.646 16.352 8 15.8 8z"></path>
    </symbol>

    <symbol viewBox="0 0 20 20" id="locked">
      <path d="M15.8 8H14V5.6C14 2.703 12.665 1 10 1 7.334 1 6 2.703 6 5.6V8H4c-.553 0-1 .646-1 1.199V17c0 .549.428 1.139.951 1.307l1.197.387C5.672 18.861 6.55 19 7.1 19h5.8c.549 0 1.428-.139 1.951-.307l1.196-.387c.524-.167.953-.757.953-1.306V9.199C17 8.646 16.352 8 15.8 8zM12 8H8V5.199C8 3.754 8.797 3 10 3c1.203 0 2 .754 2 2.199V8z"/>
    </symbol>

    <symbol viewBox="0 0 20 20" id="close">
      <path d="M14.348 14.849c-.469.469-1.229.469-1.697 0L10 11.819l-2.651 3.029c-.469.469-1.229.469-1.697 0-.469-.469-.469-1.229 0-1.697l2.758-3.15-2.759-3.152c-.469-.469-.469-1.228 0-1.697.469-.469 1.228-.469 1.697 0L10 8.183l2.651-3.031c.469-.469 1.228-.469 1.697 0 .469.469.469 1.229 0 1.697l-2.758 3.152 2.758 3.15c.469.469.469 1.229 0 1.698z"/>
    </symbol>

    <symbol viewBox="0 0 20 20" id="large-arrow">
      <path d="M13.25 10L6.109 2.58c-.268-.27-.268-.707 0-.979.268-.27.701-.27.969 0l7.83 7.908c.268.271.268.709 0 .979l-7.83 7.908c-.268.271-.701.27-.969 0-.268-.269-.268-.707 0-.979L13.25 10z"/>
    </symbol>

    <symbol viewBox="0 0 20 20" id="large-arrow-down">
      <path d="M17.418 6.109c.272-.268.709-.268.979 0s.271.701 0 .969l-7.908 7.83c-.27.268-.707.268-.979 0l-7.908-7.83c-.27-.268-.27-.701 0-.969.271-.268.709-.268.979 0L10 13.25l7.418-7.141z"/>
    </symbol>


    <symbol viewBox="0 0 24 24" id="jump-to">
      <path d="M19 7v4H5.83l3.58-3.59L8 6l-6 6 6 6 1.41-1.41L5.83 13H21V7z"/>
    </symbol>

    <symbol viewBox="0 0 24 24" id="expand">
      <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/>
    </symbol>

  </defs>
</svg>
<?php include "views/feedback_bar.php"; ?>
<?php include "views/navigation_bar.php"; ?>
<div id="swagger-ui"></div>

<script src="vendor/swagger/swagger-ui-bundle.js"> </script>
<script src="vendor/swagger/swagger-ui-standalone-preset.js"> </script>
<script>
window.onload = function() {
  // Build a system
  const ui = SwaggerUIBundle({
     url: "https://datamed.org/datamedswagger.json",
    dom_id: '#swagger-ui',
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "StandaloneLayout"
  })

  window.ui = ui
}
</script>
</body>

</html>
