<?php

// Import Repository Classes.
require_once dirname(__FILE__) . '/repository/Repository.php';

class Repositories_rep {

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
                , new RepRepository());

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
