<?php

// Import Repository Classes.
require_once dirname(__FILE__) . '/repository/ArrayExpress.php';
require_once dirname(__FILE__) . '/repository/DbGap.php';
require_once dirname(__FILE__) . '/repository/Gemma.php';
require_once dirname(__FILE__) . '/repository/Geo.php';
require_once dirname(__FILE__) . '/repository/Lincs.php';
require_once dirname(__FILE__) . '/repository/Pdb.php';
require_once dirname(__FILE__) . '/repository/Sra.php';
require_once dirname(__FILE__) . '/repository/Bioproject.php';
require_once dirname(__FILE__) . '/repository/ClinicalTrials.php';
require_once dirname(__FILE__) . '/repository/Dryad.php';
require_once dirname(__FILE__) . '/repository/Cvrg.php';
require_once dirname(__FILE__) . '/repository/Neuromorpho.php';
require_once dirname(__FILE__) . '/repository/Dataverse.php';
require_once dirname(__FILE__) . '/repository/Peptideatlas.php';
require_once dirname(__FILE__) . '/repository/Ctn.php';
require_once dirname(__FILE__) . '/repository/Cia.php';
require_once dirname(__FILE__) . '/repository/Mpd.php';
require_once dirname(__FILE__) . '/repository/Niddkcr.php';
require_once dirname(__FILE__) . '/repository/openFMRI.php';
require_once dirname(__FILE__) . '/repository/NURSA.php';
require_once dirname(__FILE__) . '/repository/Physiobank.php';
require_once dirname(__FILE__) . '/repository/Proteomexchange.php';
require_once dirname(__FILE__) . '/repository/Yped.php';

class Repositories {

    private $repositories;

    // Returns the list of all repository objects.
    public function getRepositories() {
        return $this->repositories;
    }

    // Returns a repository bu id.
    public function getRepository($id) {
        foreach ($this->repositories as $repository) {
            if ($repository->id == $id) {
                return $repository;
            }
        }
    }

    private $searchFields;

    // Returns all available search fields in all datasets.
    public function getSearchFields() {
        return $this->searchFields;
    }

    function __construct() {
        $this->repositories = [];
        array_push($this->repositories
                , new DbGapRepository()
                , new PdbRepository()
                , new GeoRepository()
                , new LincsRepository()
                , new GemmaRepository()
                , new ArrayExpressRepository()
                , new SraRepository()
                , new BioprojectRepository()
                , new ClinicalTrialsRepository()
                , new DryadRepository()
                , new CvrgRepository()
                , new DataverseRepository()
                , new NeuromorphoRepository()
                , new PeptideatlasRepository()
                , new CtnRepository()
                , new CiaRepository()
                , new MpdRepository()
                , new NiddkcrRepository()
                , new openFMRIRepository()
                , new NursaRepository()
                , new PhysiobankRepository()
                , new ProteomexchangeRepository()
                , new YpedRepository());

        $this->searchFields = [];
        foreach ($this->repositories as $repository) {
            foreach ($repository->search_fields as $field) {
                // Truncates duplicate elements.
                if (!in_array($field, $this->searchFields)) {
                    array_push($this->searchFields, $field);
                }
            }
        }
    }

}

?>
