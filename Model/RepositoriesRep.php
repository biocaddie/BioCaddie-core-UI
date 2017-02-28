<?php

// Import Repository Classes.
/*
 * for repo search
 */
require_once dirname(__FILE__) . '/RepoRepository.php';

class RepositoriesRep {

    private $repositories;

    // Returns the list of all repository objects.
    public function getRepositories() {
        return $this->repositories;
    }

    private $searchFields;

    // Returns all available search fields in all datasets.
    public function getSearchFields() {
        return $this->searchFields;
    }

    function __construct() {
        $this->repositories = [];
        array_push($this->repositories
                , new RepoRepository());

        $this->searchFields = [];
        foreach ($this->repositories as $repository) {
            foreach ($repository->searchFields as $field) {
                // Truncates duplicate elements.
                if (!in_array($field, $this->searchFields)) {
                    array_push($this->searchFields, $field);
                }
            }
        }
    }

}

?>
