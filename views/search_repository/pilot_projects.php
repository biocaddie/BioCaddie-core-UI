<?php

function partialPilotProjects($searchBuilder) { ?>
    <?php if ($searchBuilder->getCurrentRepository() == '0001'): ?>

        <a  target="_blank" href="./gwas/gwas_result.php?query1=<?php echo $searchBuilder->getQuery() ?>&query2=&query3=">
            <img src="./img/pilot-projects/GWAS.png" alt="GWAS" class="pilot-logo">
        </a>

    <?php endif ?>
    <?php if ($searchBuilder->getCurrentRepository() == '0002'): ?>

        <a target="_blank" href="http://datamed.biocaddie.org/iseedelve/pdb_v2/?q=<?php echo $searchBuilder->getQuery() ?>">
            <img src="./img/pilot-projects/iSEE-DELVE.png" alt="iSEE-DELVE" class="pilot-logo">
        </a>
    <?php endif ?>
<?php } ?>