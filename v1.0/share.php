<?php

require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/config/datasources.php';
require_once dirname(__FILE__) . '/search/Repositories.php';
require_once dirname(__FILE__) . '/search/ElasticSearch.php';
require_once dirname(__FILE__) . '/search/SearchBuilder.php';

//ini_set('display_errors', 0);
//ini_set('display_startup_errors', 1);

$repositoryHolder = new Repositories();

if (isset($_POST['radio-share'])) {
    $shareType = $_POST['radio-share'];
    $sharedData = '';
    $selectedRows = $_POST['selected-rows'] == '' ? [] : explode(",", $_POST['selected-rows']);

    $newLine = $shareType == "file" || $shareType == "citation" ? "\r\n" : "<br />";
    $rowIndex = 0;

    // File
    if ($shareType == "file") {
        try {
            if (count($selectedRows) == 0) {
                $searchBuilder = new SearchBuilder(true);
                $results = $searchBuilder->getSearchResults();
                foreach ($results as $row) {
                    $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

                    foreach ($row as $field) {
                        if (trim($field, " \t") !== "" && substr($field, 0, 16) === "display-item.php") {
                            $data = explode("&", str_replace('display-item.php?', '', $field));

                            $repositoryId = str_replace('repository=', '', $data[0]);
                            $repository = $repositoryHolder->getRepository($repositoryId);
                            $itemId = str_replace('id=', '', $data[2]);
                            $rowResults = readItem($repository, $itemId);

                            foreach ($rowResults as $rowField) {
                                if (trim($rowField, " \t") !== "") {
                                    $sharedData .= trim($rowField, " \t") . $newLine;
                                }
                            }
                            break;
                        }
                    }
                    $sharedData .= $newLine;
                    $rowIndex += 1;
                }
            } else {
                foreach ($selectedRows as $rowString) {
                    $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);
                    $repository = $repositoryHolder->getRepository($repositoryId);
                    $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
                    $results = readItem($repository, $itemId);

                    $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

                    foreach ($results as $field) {
                        if (trim($field, " \t") !== "") {
                            $sharedData .= trim($field, " \t") . $newLine;
                        }
                    }
                    $sharedData .= $newLine;
                    $rowIndex += 1;
                }
            }
        } catch (Exception $e) {
            $sharedData = 'Caught exception: ' . $e->getMessage() . "\n";
            $sharedData .= 'Line: ' . $e->getLine() . "\n";
            $sharedData .= 'Trace: ' . $e->getTraceAsString() . "\n";
        }

        header('Content-Disposition: attachment; filename="biocaddie-share.txt"');
        header('Content-Type: text/plain');
        header('Content-Length: ' . strlen($sharedData));
        header('Connection: close');
        echo $sharedData;
    }
// Email
    elseif ($shareType == "email") {
        if (count($selectedRows) == 0) {
            $searchBuilder = new SearchBuilder(true);
            $results = $searchBuilder->getSearchResults();
            foreach ($results as $row) {
                $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

                foreach ($row as $field) {
                    if (trim($field, " \t") !== "" && substr($field, 0, 16) === "display-item.php") {
                        $data = explode("&", str_replace('display-item.php?', '', $field));

                        $repositoryId = str_replace('repository=', '', $data[0]);
                        $repository = $repositoryHolder->getRepository($repositoryId);
                        $itemId = str_replace('id=', '', $data[2]);
                        $rowResults = readItem($repository, $itemId);

                        foreach ($rowResults as $rowField) {
                            if (trim($rowField, " \t") !== "") {
                                $sharedData .= trim($rowField, " \t") . $newLine;
                            }
                        }
                        $sharedData .= trim($field, " \t") . $newLine;
                        break;
                    }
                }
                $sharedData .= $newLine;
                $rowIndex += 1;
            }
        } else {
            foreach ($selectedRows as $rowString) {
                $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);
                $repository = $repositoryHolder->getRepository($repositoryId);
                $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
                $results = readItem($repository, $itemId);

                $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

                foreach ($results as $field) {
                    $sharedData .= trim($field, " \t") . $newLine;
                }
                $sharedData .= $newLine;
                $rowIndex += 1;
            }
        }

        require_once dirname(__FILE__) . '/vendor/swiftmailer/swiftmailer/lib/swift_required.php';

        $from = 'biocaddie.mail@gmail.com';
        $to = $_POST['EmailAddress'];
        $subject = isset($_POST['EmailSubject']) ? ' - ' . $_POST['EmailSubject'] : '';
        $body = '<p>' . $_POST['EmailBody'] . '</p>' . $sharedData;

        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
                ->setUsername('biocaddie.mail@gmail.com')
                ->setPassword('biocaddie4050@');

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance('bioCaddie Data Export' . $subject)
                ->setFrom(array($from => 'bioCaddie'))
                ->setTo(array($to))
                ->setBody($body)
                ->setContentType("text/html");

        $result = $mailer->send($message);
    }
// Collections
    elseif ($shareType == "collections") {
        $_SESSION['selected-rows'] = $_POST['selected-rows'];
        echo "<script> parent.self.location = \"collections.php\";</script>";
    }
// Citation Managers
    elseif ($shareType == "citation") {
        if (count($selectedRows) == 0) {
            $searchBuilder = new SearchBuilder(true);
            $results = $searchBuilder->getSearchResults();
            foreach ($results as $row) {
                $sharedData .= $newLine;

                foreach ($row as $field) {
                    if (trim($field, " \t") !== "" && substr($field, 0, 16) === "display-item.php") {
                        $data = explode("&", str_replace('display-item.php?', '', $field));

                        $repositoryId = str_replace('repository=', '', $data[0]);
                        $repository = $repositoryHolder->getRepository($repositoryId);
                        $itemId = str_replace('id=', '', $data[2]);
                        $rowResults = readCitationItem($repository, $itemId);

                        foreach ($rowResults as $rowField) {
                            if (trim($rowField, " \t") !== "") {
                                $sharedData .= trim($rowField, " \t") . $newLine;
                            }
                        }
                        break;
                    }
                }
                $sharedData .= $newLine;
                $rowIndex += 1;
            }
        } else {
            foreach ($selectedRows as $rowString) {
                $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);
                $repository = $repositoryHolder->getRepository($repositoryId);
                $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
                $results = readCitationItem($repository, $itemId);
                $sharedData .= $newLine;

                foreach ($results as $field) {
                    if (trim($field, " \t") !== "") {
                        $sharedData .= trim($field, " \t") . $newLine;
                    }
                }
                $rowIndex += 1;
            }
        }

        header('Content-Disposition: attachment; filename="biocaddie-share.nbib"');
        header('Content-Type: text/plain');
        header('Content-Length: ' . strlen($sharedData));
        header('Connection: close');
        echo $sharedData;
    }
}

function readItem($repository, $itemId) {
    $searchResults = [];

    $search = new ElasticSearch();
    $search->search_fields = ['_id'];
    $search->query = $itemId;
    $search->filter_fields = [];
    $search->es_index = $repository->index;
    $search->es_type = $repository->type;

    $results = $search->getSearchResult();

    $rows = $results['hits']['hits'][0]['_source'];

    foreach ($repository->core_fields as $field) {
        $keys = explode('.', $field);

        if (count($keys) == 1) {
            $repositoryValue = array_key_exists($keys[0], $rows) ? json_encode($keys[0]) . ': ' . $rows[$keys[0]] : '';
        } else if (count($keys) == 2) {
            $setItem = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : [];
            $repositoryValue = array_key_exists($keys[1], $setItem) ? json_encode($keys[1]) . ': ' . json_encode($setItem[$keys[1]]) : '';
        } else if (count($keys) == 3) {
            $repositoryValue = json_encode($keys[2]) . ': ' . $rows[$keys[0]][$keys[1]][0][$keys[2]];
        }

        $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
        $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '\/' => '/', '\n' => '', '<p>' => '', '</p>' => '', '<P>' => '', '</P>' => '', '<i>' => '', '</i>' => '', '<I>' => '', '</I>' => '', '<b>' => '', '</b>' => '', '<B>' => '', '</B>' => ''];
        $searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);
    }

    return $searchResults;
}

function readCitationItem($repository, $itemId) {
    $searchResults = [];

    $search = new ElasticSearch();
    $search->search_fields = ['_id'];
    $search->query = $itemId;
    $search->filter_fields = [];
    $search->es_index = $repository->index;
    $search->es_type = $repository->type;

    $results = $search->getSearchResult();

    $rows = $results['hits']['hits'][0]['_source'];

    foreach ($repository->core_fields as $field) {
        $keys = explode('.', $field);
        $citationField = "";

        if (count($keys) == 1) {
            $repositoryValue = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : '';
            $citationField = strtoupper(substr($keys[0], 0, 4));
        } else if (count($keys) == 2) {
            $setItem = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : [];
            $repositoryValue = array_key_exists($keys[1], $setItem) ? json_encode($setItem[$keys[1]]) : '';
            $citationField = strtoupper(substr($keys[1], 0, 4));
        } else if (count($keys) == 3) {
            $repositoryValue = $rows[$keys[0]][$keys[1]][0][$keys[2]];
            $citationField = strtoupper(substr($keys[2], 0, 4));
        }

        $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
        $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '\/' => '/', '\n' => '', '<p>' => '', '</p>' => '', '<P>' => '', '</P>' => '', '<i>' => '', '</i>' => '', '<I>' => '', '</I>' => '', '<b>' => '', '</b>' => '', '<B>' => '', '</B>' => ''];
        $value = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);

        if (strpos($citationField, "TITL") !== false) {
            $searchResults[$citationField] = "TI  - " . $value;
        } else if (strpos($citationField, "DATE") !== false) {
            $searchResults[$citationField] = "DP  - " . $value;
        } else {
            $fieldLen = strlen($citationField);
            $spaceReplicate = 4 - ($fieldLen > 4 ? 4 : $fieldLen);
            $searchResults[$citationField] = $citationField . str_repeat(" ", $spaceReplicate) . '- ' . $value;
        }
    }

    return $searchResults;
}

?>