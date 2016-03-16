<?php

function partialResult($service) {
    if ($service->getRepositoryId() == "0001") {
        require_once dirname(__FILE__) . '/repositories/DbGap.php';
    } elseif ($service->getRepositoryId() == "0002") {
        require_once dirname(__FILE__) . '/repositories/Pdb.php';
    } elseif ($service->getRepositoryId() == "0003") {
        require_once dirname(__FILE__) . '/repositories/Geo.php';
    } elseif ($service->getRepositoryId() == "0004") {
        require_once dirname(__FILE__) . '/repositories/Lincs.php';
    } elseif ($service->getRepositoryId() == "0005") {
        require_once dirname(__FILE__) . '/repositories/Gemma.php';
    } elseif ($service->getRepositoryId() == "0006") {
        require_once dirname(__FILE__) . '/repositories/ArrayExpress.php';
    } elseif ($service->getRepositoryId() == "0007") {
        require_once dirname(__FILE__) . '/repositories/Sra.php';
    } elseif ($service->getRepositoryId() == "0008") {
        require_once dirname(__FILE__) . '/repositories/Bioproject.php';
    }elseif ($service->getRepositoryId() == "0009") {
        require_once dirname(__FILE__) . '/repositories/ClinicalTrials.php';
    }elseif ($service->getRepositoryId() == "0010") {
        require_once dirname(__FILE__) . '/repositories/Dryad.php';
    }elseif ($service->getRepositoryId() == "0011") {
        require_once dirname(__FILE__) . '/repositories/Cvrg.php';
    }elseif ($service->getRepositoryId() == "0012") {
        require_once dirname(__FILE__) . '/repositories/Dataverse.php';
    }elseif ($service->getRepositoryId() == "0013") {
        require_once dirname(__FILE__) . '/repositories/Neuromorpho.php';
    } elseif ($service->getRepositoryId() == "0014") {
        require_once dirname(__FILE__) . '/repositories/Peptideatlas.php';
    }elseif ($service->getRepositoryId() == "0015") {
        require_once dirname(__FILE__) . '/repositories/Ctn.php';
    }elseif ($service->getRepositoryId() == "0016") {
        require_once dirname(__FILE__) . '/repositories/Cia.php';
    }elseif ($service->getRepositoryId() == "0017") {
        require_once dirname(__FILE__) . '/repositories/Mpd.php';
    }elseif ($service->getRepositoryId() == "0018") {
        require_once dirname(__FILE__) . '/repositories/Niddkcr.php';
    }elseif ($service->getRepositoryId() == "00019") {
        require_once dirname(__FILE__) . '/repositories/openFMRI.php';
    }elseif ($service->getRepositoryId() == "00020") {
        require_once dirname(__FILE__) . '/repositories/Nursa.php';
    }elseif ($service->getRepositoryId() == "00023") {
        require_once dirname(__FILE__) . '/repositories/Yped.php';
    }
    elseif ($service->getRepositoryId() == "0021") {
        require_once dirname(__FILE__) . '/repositories/Physiobank.php';
    }
    elseif ($service->getRepositoryId() == "0022") {
        require_once dirname(__FILE__) . '/repositories/Proteomexchange.php';
    }
    echo displayResult($service);
}
?>
