<?php
$pageTitle = "Collections";

require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/database/UserCollection.php';
require_once dirname(__FILE__) . '/database/Collection.php';
require_once dirname(__FILE__) . '/Model/DBController.php';
require_once dirname(__FILE__) . '/config/datasources.php';
require_once dirname(__FILE__) . '/Model/Repositories.php';
require_once dirname(__FILE__) . '/Model/ElasticSearch.php';
require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/Model/Repositories.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

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
                                $collection->setRepository($repository->repoShowName);
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
                        $collection->setRepository($repository->repoShowName);
                        $collection->setCollectionId($collectionID);
                        $collection->setCreateTime($create_time);

                        $collection->AddCollectionItem($dbconn);
                    }
                }
            }
        }

        if($error==NULL){
            echo "<script> parent.self.location = \"manage_collections.php?name=$collection_name\";</script>";
        }

    }
}


function readItem($repository, $itemId, $indexTypeHeader)
{
    $searchResults = [];

    $input_array= [];
    $input_array['esIndex'] = $repository->index;
    $input_array['esType'] = $repository->type;
    $input_array['searchFields'] = ['_id'];
    $input_array['query'] = $itemId;
    $input_array['filterFields'] = [];

    $search = new ElasticSearch($input_array);
    $search->setSearchResult();
    $results = $search->getSearchResult();


    $repoKey = explode("_", $results['hits']['hits'][0]['_index'])[0] . '_' . $results['hits']['hits'][0]['_type'];
    $rows = $results['hits']['hits'][0]['_source'];

    foreach(array_keys($rows) as $key){
        if((array_keys($rows[$key]))==NULL){
            $field = $key;
            $setItem = $rows[$key];
            $repositoryValue = json_encode($setItem);

            $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
            $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '\/' => '/', '\n' => '', '<p>' => '', '</p>' => '', '<P>' => '', '</P>' => '', '<i>' => '', '</i>' => '', '<I>' => '', '</I>' => '', '<b>' => '', '</b>' => '', '<B>' => '', '</B>' => ''];
            $searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);

        }else{
            foreach(array_keys($rows[$key]) as $subkey) {
                $field = $key.".".$subkey;
                $setItem = $rows[$key];
                $repositoryValue = json_encode($setItem[$subkey]);

                $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
                $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '\/' => '/', '\n' => '', '<p>' => '', '</p>' => '', '<P>' => '', '</P>' => '', '<i>' => '', '</i>' => '', '<I>' => '', '</I>' => '', '<b>' => '', '</b>' => '', '<B>' => '', '</B>' => ''];
                $searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);
            }
        }


    }

    $searchResults['ref'] = 'display-item.php?repository=' . $indexTypeHeader[$repoKey][3] . '&id=' . $results['hits']['hits'][0]["_id"];
    return $searchResults;
}

?>


<?php
include dirname(__FILE__) . '/views/header.php';
include dirname(__FILE__) . '/views/collections/collections.php';

/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/collections.js"];

include dirname(__FILE__) . '/views/footer.php'; ?>
