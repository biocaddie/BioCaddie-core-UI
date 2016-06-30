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


    <link rel="icon" href="./img/favicon.png">

    <!--jquery-->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"/>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>


    <!--bootstrap-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="./css/theme.css" rel="stylesheet">
    <link href="./css/expand.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="./js/ie8-responsive-file-warning.js"></script>
    <![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script type="text/javascript" src="./js/js.cookie.js"></script>
    
    <!---For sign in using google account-->
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="829717013649-0j7el8re8ka2d5tgqm87c5msek06kgmu.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script type="text/javascript" src="./js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="./js/global.scripts.js"></script>

    <!---For contact us generating captcha-->
    <script language="JavaScript" src="./js/gen_validatorv31.js" type="text/javascript"></script>

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


<body>

<div class="container">
    <div class="row" style="padding: 5px 0">
        <div class="col-sm-2">
            <div class="hidden-xs">
                <a href="index.php">
                    <img src="./img/biocaddie_png2.png" alt="Mountain View" class="header-logo" style="height: 50px">
                </a>
            </div>
            <div class="visible-xs text-center" style="margin-bottom: 10px;">
                <a href="index.php">
                    <img src="./img/datamed-green-medium-light-transparent.png" alt="DataMed BioCaddie Logo" class="header-logo" style="height: 50px">
                </a>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="hidden-xs text-right">
                <div style="margin-right: 10px; line-height: 50px; display: inline;">
                    <span style="font-size: 14px;"><span style="color: #2CA02C">bio</span>medical and health<span style="color: #2CA02C">CA</span>re <span style="color: #2CA02C">D</span>ata <span style="color: #2CA02C">D</span>iscovery <span style="color: #2CA02C">I</span>ndex <span style="color: #2CA02C">E</span>cosystem</span>
                </div>
                <div style="display: inline;">
                    <a href="https://biocaddie.org" target="_blank">
                    <img src="./img/biocaddie-logo-transparent.png" alt="Mountain View" class="header-tag pull-right" style="height: 50px;">
                </a>
                </div>
            </div>
            <div class="visible-xs text-center">
                <div style="margin-bottom: 10px;">
                    <a href="https://biocaddie.org" target="_blank">
                        <img src="./img/biocaddie-logo-transparent.png" alt="Mountain View" class="header-tag" style="height: 50px;">
                    </a>
                </div>
                <div>
                    <span style="font-size: 14px;"><span style="color: #2CA02C">bio</span>medical and health<span style="color: #2CA02C">CA</span>re <span style="color: #2CA02C">D</span>ata <span style="color: #2CA02C">D</span>iscovery <span style="color: #2CA02C">I</span>ndex <span style="color: #2CA02C">E</span>cosystem</span>
                </div>
            </div>                        
        </div>                
    </div>
</div>

<div class="header clearfix">
    <nav class="navbar navbar-inverse">
        <div class="container">
            <ul class="nav navbar-nav navbar-right">
                <?php if (basename(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING), '.php') != 'index'): ?>
                    <li role="presentation" class="active"><a href="index.php">Home</a></li>
                <?php endif; ?>
                <li role="presentation" class="active"><a href="./about.php">About</a></li>
                <!--<li role="presentation" class="active"><a href="https://biocaddie.org/contact" target='_blank'>Contact</a></li>-->

                <li class="active">

                    <a href="feedback.php" class="dropdown-toggle dropdown"  role="presentation" aria-haspopup="true" aria-expanded="false">
                        Feedback<span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu dropdown">
                        <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Report an issue</a>
                            <ul class="dropdown-menu">
                                <li><a href="https://github.com/biocaddie/prototype_issues/issues" target="_blank">GitHub</a></li>
                                <li><a href="./about.php">Contact Us</a></li>
                                </ul>
                        </li>

                        <li><a href="./questionnaire.php" >Questionnaire</a></li>
                    </ul>
                          </li>
                <li class="active"><a href="./submit_repository.php" ><span data-toggle="tooltip" data-placement="bottom" title="Get your repository indexed" >Submit<span></span></a></li>
                <?php if(!isset($_SESSION['name'])){?>
                <li role="presentation" class="active"><a href="login.php" id="partial_login_a">Login</a></li>
                <?php }else{?>
                    <li role="presentation" class="active"><a href="login.php" id="partial_login_a">
                            <?php
                            echo "MyDataMed";
                            ?>
                        </a></li>
                    <li role="presentation" class="active"><a href='login.php?logout'>Logout</a></li>
                <?php }?>
            </ul>
        </div>
    </nav>
</div>