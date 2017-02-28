<?php


function partialPilotProjects($searchBuilder) {
    require_once dirname(__FILE__) . '/../../config/config.php';
    global $IseeDelve;

    if ($searchBuilder->getSelectedRepositories()!=NULL && in_array('0001', $searchBuilder->getSelectedRepositories())): ?>

        <a  target="_blank" href="./gwas/gwas_result.php?query1=<?php echo $searchBuilder->getQuery() ?>&query2=&query3=">
            <img src="./img/pilot-projects/GWAS.png" alt="GWAS" class="pilot-logo">
        </a>

    <?php endif ?>
    <?php if ($searchBuilder->getSelectedRepositories()!=NULL && in_array('0002', $searchBuilder->getSelectedRepositories())): ?>

        <a target="_blank" href="<?php echo $IseeDelve; ?>?q=<?php echo $searchBuilder->getQuery() ?>">
            <img src="./img/pilot-projects/iSEE-DELVE.png" alt="iSEE-DELVE" class="pilot-logo">
        </a>
    <?php endif ?>
<?php } ?>