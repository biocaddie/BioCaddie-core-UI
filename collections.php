<?php
require_once dirname(__FILE__) . '/config/config.php';
require_once './database/UserCollection.php';
require_once './database/Collection.php';
require_once 'dbcontroller.php';
require_once dirname(__FILE__) . '/config/datasources.php';
require_once dirname(__FILE__) . '/search/Repositories.php';
require_once dirname(__FILE__) . '/search/ElasticSearch.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';
require_once dirname(__FILE__) . '/search/Repositories.php';
//require_once dirname(__FILE__) . '/trackactivity.php';


if (!isset($_SESSION['name'])) {
    echo "<script> parent.self.location = \"login.php\";</script>";
} else {

    $repositoryHolder = new Repositories();
    $selectedRows = explode(",", $_SESSION['selected-rows']);


    $repositories = new Repositories();
    $indexTypeHeader = [];
    foreach ($repositories->getRepositories() as $repo) {
        $indexTypeHeader[$repo->index . '_' . $repo->type] = [$repo->datasource_headers,
            $repo->source,
            $repo->show_name,
            $repo->id,
            $repo->link_field,
            $repo->core_fields_show_name];
    }

    $email = $_SESSION['email'];
    $collection_name = '';
    $create_time = '';
    $setting = 'private';


    $objDBController = new DBController();
    $dbconn = $objDBController->getConn();

//New UserCollection class
    $usercollection = new UserCollection();
    $usercollection->setUserEmail($email);


//New Collection class
    $collection = new Collection();

    $collectionNames = $usercollection->getCollections($dbconn);

    if (isset($_POST['collectionsradio'])) {
        $collectionType = $_POST['collectionsradio'];
        $create_time = date("Y-m-d H:i:s");
        $collectionID = 0;

        // Add to a new collection
        if ($collectionType == "new") {
            $collection_name = $_POST['collectionName'];

            if ($collection_name == "") {
                $error[] = "Please enter collection name!";
            } else {
                if (!$usercollection->is_collection_name_exist($dbconn, $collection_name)) {

                    //Add new collection to user_collection table
                    $usercollection->setCollectionName($collection_name);
                    $usercollection->setCreateTime($create_time);
                    $usercollection->setSettings($setting);

                    // Create a new collection

                    $collectionID = $usercollection->createCollection($dbconn);

                    $usercollection->setCollectionId($collectionID);

                    //Add selected items to collection table
                    foreach ($selectedRows as $rowString) {
                        $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);
                        $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
                        $results = NULL;

                        foreach ($repositoryHolder->getRepositories() as $repository) {
                            if ($repository->id == $repositoryId) {
                                $results = readItem($repository, $itemId, $indexTypeHeader);


                                if (isset($results['dataset.title'])) {
                                    $collection->setDatasetTitle($results['dataset.title']);
                                } elseif (isset($results['dataItem.title'])) {
                                    $collection->setDatasetTitle($results['dataItem.title']);
                                }elseif(isset($results['Dataset.title'])){
                                    $collection->setDatasetTitle($results['Dataset.title']);
                                }elseif (isset($results['title'])) {
                                    $collection->setDatasetTitle($results['title']);
                                }else{
                                    $collection->setDatasetTitle(" ");
                                }

                                if (isset($results['dataset.description'])) {
                                    $collection->setDatasetDescription($results['dataset.description']);
                                } elseif (isset($results['dataItem.description'])) {
                                    $collection->setDatasetDescription($results['dataItem.description']);
                                } elseif (isset($results['Dataset.description'])) {
                                    $collection->setDatasetDescription($results['Dataset.description']);
                                }else{
                                    $collection->setDatasetDescription(" ");
                                }

                                $collection->setDatasetUrl($results['ref']);
                                $collection->setRepository($repository->show_name);
                                $collection->setCollectionId($collectionID);
                                $collection->setCreateTime($create_time);

                                $collection->AddCollectionItem($dbconn);
                            }
                        }
                    }

                } else {
                    $error[] = "Please provide a unique collection name. A collection with the same name already exists.";
                }
            }
        }
        // Add to an exisitng collection
        elseif ($collectionType == "existing") {
            // Get collection id using user email and collection name
            $collection_name = $_POST['selectName'];
            $usercollection->setCollectionName($collection_name);
            $collectionID = $usercollection->searchCollectionId($dbconn);
            $collectionID = $collectionID[0]['collection_id'];

            //Add selected items to collection table
            foreach ($selectedRows as $rowString) {
                $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);
                $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
                $results = NULL;

                foreach ($repositoryHolder->getRepositories() as $repository) {
                    if ($repository->id == $repositoryId) {
                        $results = readItem($repository, $itemId, $indexTypeHeader);

                        if (isset($results['dataset.title'])) {
                            $collection->setDatasetTitle($results['dataset.title']);
                        } elseif (isset($results['dataItem.title'])) {
                            $collection->setDatasetTitle($results['dataItem.title']);
                        }elseif(isset($results['Dataset.title'])){
                            $collection->setDatasetTitle($results['Dataset.title']);
                        }elseif (isset($results['title'])) {
                            $collection->setDatasetTitle($results['title']);
                        }else{
                            $collection->setDatasetTitle(" ");
                        }

                        if (isset($results['dataset.description'])) {
                            $collection->setDatasetDescription($results['dataset.description']);
                        } elseif (isset($results['dataItem.description'])) {
                            $collection->setDatasetDescription($results['dataItem.description']);
                        } elseif (isset($results['Dataset.description'])) {
                            $collection->setDatasetDescription($results['Dataset.description']);
                        }else{
                            $collection->setDatasetDescription(" ");
                        }

                        $collection->setDatasetUrl($results['ref']);
                        $collection->setRepository($repository->show_name);
                        $collection->setCollectionId($collectionID);
                        $collection->setCreateTime($create_time);

                        $collection->AddCollectionItem($dbconn);
                    }
                }
            }


        }

        echo "<script> parent.self.location = \"manage_collections.php?name=$collection_name\";</script>";
    }
}


function readItem($repository, $itemId, $indexTypeHeader)
{
    $searchResults = [];

    $search = new ElasticSearch();
    $search->search_fields = ['_id'];
    $search->query = $itemId;
    $search->filter_fields = [];
    $search->es_index = $repository->index;
    $search->es_type = $repository->type;

    $results = $search->getSearchResult();

    $key = explode("_", $results['hits']['hits'][0]['_index'])[0] . '_' . $results['hits']['hits'][0]['_type'];


    $rows = $results['hits']['hits'][0]['_source'];

    foreach ($repository->core_fields as $field) {
        $keys = explode('.', $field);

        if (count($keys) == 1) {
            $repositoryValue = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : '';
        } else if (count($keys) == 2) {
            $setItem = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : [];
            $repositoryValue = array_key_exists($keys[1], $setItem) ? json_encode($keys[1]) . ': ' . json_encode($setItem[$keys[1]]) : '';
        } else if (count($keys) == 3) {
            $repositoryValue = $repository->id == "0008" ? $rows[$keys[0]][$keys[1]][0][$keys[2]] : $rows[$keys[0]][$keys[1]][$keys[2]];
        }

        $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
        $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '"' => '', '\/' => '/'];
        $searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);
    }
    $searchResults['ref'] = 'display-item.php?repository=' . $indexTypeHeader[$key][3] . '&idName=' . $indexTypeHeader[$key][4] . '&id=' . $results['hits']['hits'][0]["_id"];
    return $searchResults;
}

?>


<?php
include dirname(__FILE__) . '/views/header.php';
include dirname(__FILE__) . '/views/collections/collections.php';

/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/collections.js"];

include dirname(__FILE__) . '/views/footer.php'; ?>
