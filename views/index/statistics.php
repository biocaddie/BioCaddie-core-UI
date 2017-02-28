<?php
require_once dirname(__FILE__) . '/../../config/datasources.php';
$dataTypes = getDatatypes();
$repo = getRepositoryIDMapping();
?>

<div id="statistics-panel" class="panel panel-primary panel-home">
    <div class="panel-heading">
        <strong>Statistics</strong>
        <span class="title-icon">
            <i class="fa fa-line-chart fa-fw"></i>
        </span>
    </div>
    <div class="panel-body">
        <div class="text-center">
            <div class="row">
                <div class="col-xs-6 stat-box">
                    <a href="repository_list.php">
                        <i class="fa fa-database text-info"></i>
                    </a>
                    <a class="hyperlink" href="repository_list.php">
                        <p class="hyperlink stat-value"><?php echo sizeof($repo);?></p>
                        <p class=" stat-title hyperlink">Repositories</p>
                    </a>
                </div>
                <div class="col-xs-6 stat-box">
                    <i class="fa fa-tags text-warning"></i>
                    <a class="hyperlink" href="datatypes.php">
                        <p class="hyperlink stat-value"><?php echo sizeof($dataTypes);?></p>
                        <p class="hyperlink stat-title">Data Types</p>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 stat-box">
                    <i class="fa fa-cubes text-success"></i>
                    <a class="hyperlink" href="search.php?query=&searchtype=data">
                        <p class="stat-value">  1,375037  </p><br>
                        <p class="stat-title">Datasets</p>
                    </a>
                </div>
                <div class="col-xs-6 stat-box">
                    <i class="fa fa-folder-open" style="color: #8c8c8c"></i>
                    <a class="hyperlink" href="#pilot">
                        <p class="stat-value">4</p>
                        <p class="stat-title">Pilot Projects</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>