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
                    <img src="./img/biocaddie_png2.png" alt="DataMed BioCaddie Logo" class="header-logo"
                         style="height: 50px">
                </a>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="hidden-xs text-right">
                <div style="margin-right: 10px; line-height: 50px; display: inline;">
                    <span style="font-size: 14px;"><span style="color: #2CA02C">bio</span>medical and health<span
                            style="color: #2CA02C">CA</span>re <span style="color: #2CA02C">D</span>ata <span
                            style="color: #2CA02C">D</span>iscovery <span style="color: #2CA02C">I</span>ndex <span
                            style="color: #2CA02C">E</span>cosystem</span>
                </div>
                <div style="display: inline;">
                    <a href="https://biocaddie.org" target="_blank">
                        <img src="./img/biocaddie-logo-transparent.png" alt="Mountain View"
                             class="header-tag pull-right" style="height: 50px;">
                    </a>
                </div>
            </div>
            <div class="visible-xs text-center">
                <div style="margin-bottom: 10px;">
                    <a href="https://biocaddie.org" target="_blank">
                        <img src="./img/biocaddie-logo-transparent.png" alt="Mountain View" class="header-tag"
                             style="height: 50px;">
                    </a>
                </div>
                <div>
                    <span style="font-size: 14px;"><span style="color: #2CA02C">bio</span>medical and health<span
                            style="color: #2CA02C">CA</span>re <span style="color: #2CA02C">D</span>ata <span
                            style="color: #2CA02C">D</span>iscovery <span style="color: #2CA02C">I</span>ndex <span
                            style="color: #2CA02C">E</span>cosystem</span>
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

                    <a href="feedback.php" class="dropdown-toggle dropdown" role="presentation" aria-haspopup="true"
                       aria-expanded="false">
                        Feedback<span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu dropdown">
                        <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle"
                                                                 data-toggle="dropdown">Report an issue</a>
                            <ul class="dropdown-menu">
                                <li><a href="https://github.com/biocaddie/prototype_issues/issues" target="_blank">GitHub</a>
                                </li>
                                <li><a href="./about.php">Contact Us</a></li>
                            </ul>
                        </li>

                        <li><a href="./questionnaire.php">Questionnaire</a></li>
                    </ul>
                </li>
                <li class="active"><a href="./submit_repository.php"><span data-toggle="tooltip" data-placement="bottom"
                                                                           title="Get your repository indexed">Submit<span></span></a>
                </li>
                <?php if (!isset($_SESSION['name'])) { ?>
                    <li role="presentation" class="active"><a href="./login.php" id="partial_login_a">Login</a></li>
                <?php } else { ?>
                    <li role="presentation" class="active"><a href="./login.php" id="partial_login_a">
                            <?php
                            echo "MyDataMed";
                            ?>
                        </a></li>
                    <li role="presentation" class="active"><a href="./login.php?logout">Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</div>
