<?php

function partialBreadcrumbPanel($service) {
    $query = $service->getQueryString();
    if ($query != NULL && strlen($query) > 0) {
        ?> 
        <ul class="breadcrumb">
            <li><a href="index.php" data-toggle="tooltip" data-placement="bottom" title="Return back to home page."><i class="fa fa-home"></i></a></li>
            <li>
                <a data-toggle="tooltip" data-placement="bottom" title="Search Query." href="search.php?query=<?php echo $query ?>&searchtype=data">
                    <?php echo $query ?>
                </a>
            </li>
            <li>
                <a data-toggle="tooltip" data-placement="bottom" title="Selected Repository." href="search-repository.php?query=<?php echo $query ?>&repository=<?php echo $service->getRepositoryId() ?>">
                    <?php echo $service->getRepositoryName() ?>
                </a>
            </li>
            <?php if ($service->getDatatypes() != NULL && strlen($service->getDatatypes()) > 0) { ?>
                <li>
                    <a data-toggle="tooltip" data-placement="bottom" title="Selected Datatypes." href="search.php?query=<?php echo $query ?>&searchtype=data&datatypes=<?php echo $service->getDatatypes() ?>">
                        <?php echo $service->getDatatypes() ?>
                    </a>
                </li>
            <?php } ?>
            <?php if ($service->getFilters() != NULL && strlen($service->getFilters()) > 0) { ?>
                <li>
                    <a data-toggle="tooltip" data-placement="bottom" title="Selected filters." href="search-repository.php?query=<?php echo $query ?>&repository=<?php echo $service->getRepositoryId() ?>&filters=<?php echo $service->getFilters() ?>">
                        <?php echo $service->getFilters() ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <?php
    }
    ?>    
    <?php
}
?>