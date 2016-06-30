<?php

require_once dirname(__FILE__) . '/../config/datasources.php';

require_once dirname(__FILE__) . '/Repositories.php';
require_once dirname(__FILE__) . '/ElasticSearch.php';

class SingleItemDisplayService {

    // An instance of repositories database.
    private $currentRepository;

    public function getCurrentRepository() {
        return $this->currentRepository;
    }

    private $repositoryId;

    public function getRepositoryId() {
        return $this->repositoryId;
    }

    private $itemIdName;

    public function getItemIdName() {
        return $this->itemIdName;
    }

    private $itemId;

    public function getItemId() {
        return $this->itemId;
    }

    // Current page result set.
    private $searchResults;

    public function getSearchResults() {
        return $this->searchResults;
    }

    private $itemUid;

    public function getItemUid() {
        return $this->itemUid;
    }

    private $queryString;

    public function getQueryString() {
        return $this->queryString;
    }

    private $repositoryName;

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    private $datatypes;

    public function getDatatypes() {
        return $this->datatypes;
    }

    private $filters;

    public function getFilters() {
        return $this->filters;
    }

    function __construct() {
        if (!$this->loadParameters()) {
            header('Location: index.php');
            exit;
        }
        $this->readItem();
    }

    // Load Search Query String.
    private function loadParameters() {
        $this->repositoryId = filter_input(INPUT_GET, "repository", FILTER_SANITIZE_STRING);
        if ($this->repositoryId === NULL || strlen($this->repositoryId) == 0) {
            return false;
        }

        $this->itemIdName = filter_input(INPUT_GET, "idName", FILTER_SANITIZE_STRING);
        if ($this->itemIdName === NULL || strlen($this->itemIdName) == 0) {
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
        $this->repositoryName = $this->currentRepository->show_name;
        $this->datatypes = filter_input(INPUT_GET, "datatypes", FILTER_SANITIZE_STRING);
        $this->filters = filter_input(INPUT_GET, "filters", FILTER_SANITIZE_STRING);

        return true;
    }

    // Count the total number of relavant results from the query.
    private function readItem() {
        $rows = NULL;
        $core_fields = NULL;

        $search = new ElasticSearch();
        $search->search_fields = ['_id'];
        $search->query = $this->getItemId();
        $search->filter_fields = [];
        $search->es_index = $this->getCurrentRepository()->index;
        $search->es_type = $this->getCurrentRepository()->type;
        $core_fields = $this->getCurrentRepository()->core_fields;
        $results = $search->getSearchResult();
        $rows = $results['hits']['hits'][0]['_source'];
        $this->itemUid = $results['hits']['hits'][0]['_id'];
        $this->searchResults = [];
        $external_link_icon = '&nbsp;&nbsp;&nbsp;<img style="height: 20px ;width:50px" src="./img/repositories/'. $this->getCurrentRepository()->id.'.png">';
        foreach ($core_fields as $field) {
            $keys = explode('.', $field);
            $repositoryValue = NULL;

            if (count($keys) == 1) {
                $repositoryValue = $rows[$field];
            } else if (count($keys) == 2) {
                $repositoryValue = $rows[$keys[0]][$keys[1]];
            } else if (count($keys) == 3) {

                if ($this->getCurrentRepository()->id == "0008") {

                    $repositoryValue = $rows[$keys[0]][$keys[1]][0][$keys[2]];
                } else {
                    $repositoryValue = $rows[$keys[0]][$keys[1]][$keys[2]];
                }
            }

            $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
            $replaceList = [ '{' => '', '}' => '', '[' => '', ']' => '', '"' => ''];
            $this->searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);

        }
        if ($this->getCurrentRepository()->id == "0002" || $this->getCurrentRepository()->id == "0005" ) {
            $this->searchResults[
                    $this->getCurrentRepository()->link_field] =
                     '<a href="'
                    . $this->getCurrentRepository()->source
                    . $this->searchResults['dataItem.ID']
                    . '" target="_blank">'
                    .$this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0001") {
            $this->searchResults[
                    $this->getCurrentRepository()->link_field] =
                     '<a href="'
                    . $this->getCurrentRepository()->source
                    . $this->searchResults['path']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0003") {
            $this->searchResults[
            $this->getCurrentRepository()->link_field] =
                '<a href="'
                . $this->getCurrentRepository()->source
                . $this->searchResults['dataItem.geo_accession']
                . '" target="_blank">'
                . $this->searchResults[$this->getCurrentRepository()->link_field]
                .  $external_link_icon
                . '</a>';
        }elseif($this->getCurrentRepository()->id == "0004"){

            if (isset($rows['dataset']['ID'])) {
                $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="http://lincsportal.ccs.miami.edu/datasets/#/view/'
                    .  $rows['dataset']['ID']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
            }
        }elseif($this->getCurrentRepository()->id == "0006"){
            $this->searchResults[
            $this->getCurrentRepository()->link_field] =
                '<a href="'
                . $this->getCurrentRepository()->source
                . $this->searchResults['dataset.ID']
                . '" target="_blank">'
                . $this->searchResults[$this->getCurrentRepository()->link_field]
                .  $external_link_icon
                . '</a>';
        }elseif ($this->getCurrentRepository()->id == "0007") {
            $this->searchResults[
                    $this->getCurrentRepository()->link_field] =
                     '<a href="'
                    . $this->getCurrentRepository()->source
                    . $this->searchResults['accession']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0008") {

            $this->searchResults['dataItem.title'] = reduce_dupilicate_in_title($this->searchResults['dataItem.title']);
            $this->searchResults['dataItem.title'] = '<a href="'
                    . $this->getCurrentRepository()->source
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    . '" target="_blank">'
                    . $this->searchResults['dataItem.title']
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0009") {

            $this->searchResults['Dataset.briefTitle'] =  '<a href="'
                    . $this->getCurrentRepository()->source
                    . $rows['DataSet']['identifier']
                    . '" target="_blank">'
                    . $this->searchResults['Dataset.briefTitle']
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0010") {
            $doi = '';
            foreach($rows['identifiers']['ID'] as $id){
                if(strpos($id, 'doi:') === 0){
                    $doi = 'http://dx.doi.org/'.$id;
                }
            }
            $this->searchResults['doi']=$doi;
            $this->searchResults[
            $this->getCurrentRepository()->link_field] = '<a href="'
                . $doi
                . '" target="_blank">'
                . $this->searchResults[$this->getCurrentRepository()->link_field]
                .  $external_link_icon
                . '</a>';

        } elseif ($this->getCurrentRepository()->id == "0011") {
            $this->searchResults[
                    $this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['ID']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0013") {
            $this->searchResults[
                    $this->getCurrentRepository()->link_field] = '<a href="'
                    . $this->getCurrentRepository()->source
                    . $rows['dataset']['title']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0012") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0014") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
            $this->searchResults['organism.name'] = $rows['organism'][0]['name'];
            $this->searchResults['organism.strain'] = $rows['organism'][0]['strain'];
        } elseif ($this->getCurrentRepository()->id == "0015") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['url']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
            $this->searchResults['organism.name'] = $rows['organism'][0]['name'];
            $this->searchResults['organism.strain'] = $rows['organism'][0]['strain'];
        } elseif ($this->getCurrentRepository()->id == "0016") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';

            $this->searchResults['disease.name'] = $rows['disease'][0]['name'];
            $this->searchResults['organism.name'] = $rows['organism'][0]['name'];
            $this->searchResults['organism.scientificName'] = $rows['organism'][0]['scientificName'];
        }
        elseif ($this->getCurrentRepository()->id == "0017") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';

            $dimensions = '';
            foreach ($rows['dimension'] as $dimension) {
                if (strlen($dimensions) == 0) {
                    $dimensions = $dimension['name'];
                } else {
                    $dimensions = $dimensions . '<br>' . $dimension['name'];
                }
            }
            $this->searchResults['dimension.name'] = $dimensions;
            $this->searchResults['organism.scientificName'] = $rows['organism'][0]['scientificName'];
        } elseif ($this->getCurrentRepository()->id == "0018") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';

            $diseases = '';
            foreach ($rows['disease'] as $disease) {
                if (strlen($diseases) == 0) {
                    $diseases = $disease['name'];
                } else {
                    $diseases = $diseases . '<br>' . $disease['name'];
                }
            }
            $this->searchResults['disease.name'] = $diseases;
            $this->searchResults['organism.scientificName'] = $rows['organism'][0]['scientificName'];
        } elseif ($this->getCurrentRepository()->id == "0019") {

            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';

            $this->searchResults['organism.name'] = $rows['organism'][0]['name'];
        } elseif ($this->getCurrentRepository()->id == "0020" or $this->getCurrentRepository()->id == "0023") {

            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
        } elseif ($this->getCurrentRepository()->id == "0021") {
            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';
            $this->searchResults['organism.name'] = $rows['organism'][0]['name'];
        } elseif ($this->getCurrentRepository()->id == "0022") {

            $this->searchResults[$this->getCurrentRepository()->link_field] = '<a href="'
                    . $rows['dataset']['downloadURL']
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    .  $external_link_icon
                    . '</a>';

            $persons = '';
            foreach ($rows['person'] as $person) {
                if (strlen($persons) == 0) {
                    $persons = $person['name'];
                } else {
                    $persons = $persons . '<br>' . $person['name'];
                }
            }
            $this->searchResults['person.name'] = $persons;

            $organisms = '';
            foreach ($rows['organism'] as $organism) {
                if (strlen($organisms) == 0) {
                    $organisms = $organism['name'];
                } else {
                    $organisms = $organisms . '<br>' . $organism['name'];
                }
            }
            $this->searchResults['organism.name'] = $organisms;

            $publications = '';

            foreach ($rows['publication'] as $publication) {
                if (strlen($publications) == 0) {
                    $publications = $publication;
                } else {
                    $publications = $publications . '<br>' . $publication;
                }
            }
            $this->searchResults['publication.name'] = $publications;

            $keywords = '';
            foreach ($rows['keywords'] as $organism) {
                if (strlen($keywords) == 0) {
                    $keywords = $organism;
                } else {
                    $keywords = $keywords . '<br>' . $organism;
                }
            }
            $this->searchResults['keywords'] = $keywords;

            $instruments = '';
            foreach ($rows['instrument'] as $organism) {
                if (strlen($instruments) == 0) {
                    $instruments = $organism['name'];
                } else {
                    $instruments = $instruments . '<br>' . $organism['name'];
                }
            }
            $this->searchResults['instrument'] = $instruments;
        } else {


            $this->searchResults[
                    $this->getCurrentRepository()->link_field] = '<a href="'
                    . $this->getCurrentRepository()->source
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    . '" target="_blank">'
                    . $this->searchResults[$this->getCurrentRepository()->link_field]
                    . '</a>';
        }
    }

}
