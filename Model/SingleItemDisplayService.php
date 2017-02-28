<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/11/16
 * Time: 10:06 AM
 */
require_once dirname(__FILE__) . '/../config/datasources.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';
require_once dirname(__FILE__) . '/Repositories.php';

class SingleItemDisplayService {

    private $currentRepository;

    public function getCurrentRepository() {
        return $this->currentRepository;
    }

    private $repositoryId;

    public function getRepositoryId() {
        return $this->repositoryId;
    }

    private $itemId;

    public function getItemId() {
        return $this->itemId;
    }

    private $repositoryName;

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    private $queryString;

    public function getQuery() {
        return $this->queryString;
    }

    /*
     * Current page result set.
     */

    private $searchResults;

    public function getSearchResults() {
        return $this->searchResults;
    }

    /*
     * all the search type is set to data but not repository
     * this is required to call the views/search_panel.php
     */

    public function getSearchType() {
        return 'data';
    }

    private function loadParameters() {
        $this->repositoryId = filter_input(INPUT_GET, "repository", FILTER_SANITIZE_STRING);
        if ($this->repositoryId === NULL || strlen($this->repositoryId) == 0) {
            return false;
        }

        $this->itemId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
        if ($this->itemId === NULL || strlen($this->itemId) == 0) {
            return false;
        }

        $repositoryHolder = new Repositories();
        foreach ($repositoryHolder->getRepositories() as $repository) {
            if ($repository->id == $this->repositoryId) {
                $this->currentRepository = $repository;
                break;
            }
        }

        if (!isset($this->currentRepository)) {
            return false;
        }
        $this->queryString = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);
        return true;
    }

    function __construct() {
        if (!$this->loadParameters()) {
            header('Location: index.php');
            exit;
        }
        $this->readItem();
    }

    /*
     * get the dataset info and process it.
     */
    private function readItem() {
        $input_array = ['esIndex' => $this->getCurrentRepository()->index, 'searchFields' => ['_id'], 'query' => $this->itemId];
        $search = new ElasticSearch($input_array);
        $search->setSearchResult();
        $result = $search->getSearchResult();

        $row = $result['hits']['hits'][0]['_source'];
        $this->searchResults = $row;
        //var_dump($this->searchResults);
    }

    /*
     * may not need this function
     */

    public function getDisplayItemData() {
        return $this->getCurrentRepository()->getDisplayItemView($this->searchResults);
    }

}
