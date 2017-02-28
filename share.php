<?php

require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/config/datasources.php';
require_once dirname(__FILE__) . '/Model/Repositories.php';
require_once dirname(__FILE__) . '/Model/ElasticSearch.php';
require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/Model/ConstructSearchView.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (error_reporting() === 0) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$shareType = $_POST['radio-share'];
if (!isset($shareType)) {
    return;
}

$selectedRows = isset($_POST['selected-rows']) ? explode(",", $_POST['selected-rows']) : [];

// File
if ($shareType == "file") {
    try {
        if (count($selectedRows) == 0) {
            $sharedData = shareFileAll();
        } else {
            $sharedData = shareFileSelective($selectedRows);
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
    ob_clean();
    echo $sharedData;
}
// Email
elseif ($shareType == "email") {
    try {
        if (count($selectedRows) == 0) {
            $sharedData = shareEmailAll();
        } else {
            $sharedData = shareEmailSelective($selectedRows);
        }
    } catch (Exception $e) {
        $sharedData = 'Caught exception: ' . $e->getMessage() . "\n";
        $sharedData .= 'Line: ' . $e->getLine() . "\n";
        $sharedData .= 'Trace: ' . $e->getTraceAsString() . "\n";
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
    try {
        if (count($selectedRows) == 0) {
            $sharedData = shareCitationAll();
        } else {
            $sharedData = shareCitationSelective($selectedRows);
        }
    } catch (Exception $e) {
        $sharedData = 'Caught exception: ' . $e->getMessage() . "\n";
        $sharedData .= 'Line: ' . $e->getLine() . "\n";
        $sharedData .= 'Trace: ' . $e->getTraceAsString() . "\n";
    }

    header('Content-Disposition: attachment; filename="biocaddie-share.nbib"');
    header('Content-Type: text/plain');
    header('Content-Length: ' . strlen($sharedData));
    header('Connection: close');
    ob_clean();
    echo $sharedData;
}

function shareFileSelective($selectedRows) {
    $newLine = "\r\n";
    return shareSelective($selectedRows, $newLine);
}

function shareFileAll() {
    $rowIndex = 0;
    $newLine = "\r\n";
    $sharedData = '';
    $repositories = new Repositories();

    $searchBuilder = new SearchBuilder();
    $searchBuilder->setRangeForSharing();
    $searchBuilder->searchSelectedRepo();
    $searchView = new ConstructSearchView($searchBuilder);
    $results = $searchView->getSearchResults();

    foreach ($results as $row) {
        $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

        foreach ($row as $field) {
            if (trim($field, " \t") !== "" && substr($field, 0, 16) === "display-item.php") {
                $data = explode("&", str_replace('display-item.php?', '', $field));

                $repositoryId = str_replace('repository=', '', $data[0]);
                $repository = $repositories->getRepository($repositoryId);
                $itemId = str_replace('id=', '', $data[1]);
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
    return $sharedData;
}

function shareEmailSelective($selectedRows) {
    $newLine = "<br />";
    return shareSelective($selectedRows, $newLine);
}

function shareSelective($selectedRows, $newLine) {
    $rowIndex = 0;
    $sharedData = '';
    $repositoryHolder = new Repositories();

    foreach ($selectedRows as $rowString) {
        $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;
        $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);

        $repository = $repositoryHolder->getRepository($repositoryId);
        $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
        $results = readItem($repository, $itemId);

        foreach ($results as $field) {
            if (trim($field, " \t") !== "") {
                $sharedData .= trim($field, " \t") . $newLine;
            }
        }
        $sharedData .= $newLine;
        $rowIndex += 1;
    }
    return $sharedData;
}

function shareEmailAll() {
    $rowIndex = 0;
    $newLine = "<br />";
    $sharedData = '';
    $repositories = new Repositories();

    $searchBuilder = new SearchBuilder();
    $searchBuilder->setRangeForSharing();
    $searchBuilder->searchSelectedRepo();
    $searchView = new ConstructSearchView($searchBuilder);
    $results = $searchView->getSearchResults();   

    foreach ($results as $row) {
        $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

        foreach ($row as $field) {
            if (trim($field, " \t") !== "" && substr($field, 0, 16) === "display-item.php") {
                $data = explode("&", str_replace('display-item.php?', '', $field));

                $repositoryId = str_replace('repository=', '', $data[0]);
                $repository = $repositories->getRepository($repositoryId);
                $itemId = str_replace('id=', '', $data[1]);
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
    return $sharedData;
}

function shareCitationSelective($selectedRows) {
    $newLine = "\r\n";
    $rowIndex = 0;
    $sharedData = '';
    $repositoryHolder = new Repositories();

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
    return $sharedData;
}

function shareCitationAll() {
    $rowIndex = 0;
    $newLine = "\r\n";
    $sharedData = '';
    $repositories = new Repositories();

    $searchBuilder = new SearchBuilder();
    $searchBuilder->setRangeForSharing();
    $searchBuilder->searchSelectedRepo();
    $searchView = new ConstructSearchView($searchBuilder);
    $results = $searchView->getSearchResults();
    
    foreach ($results as $row) {
        $sharedData .= $newLine;

        foreach ($row as $field) {
            if (trim($field, " \t") !== "" && substr($field, 0, 16) === "display-item.php") {
                $data = explode("&", str_replace('display-item.php?', '', $field));

                $repositoryId = str_replace('repository=', '', $data[0]);
                $repository = $repositories->getRepository($repositoryId);
                $itemId = str_replace('id=', '', $data[1]);
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
    return $sharedData;
}

function readItem($repository, $itemId) {
    $rows = searchDataItem($repository, $itemId);
    $searchResults = [];

    foreach (array_keys($rows) as $key) {
        if (is_array($rows[$key])) {
            foreach (array_keys($rows[$key]) as $subkey) {
                $setItem = $rows[$key];
                $repositoryValue = json_encode($subkey) . ': ' . json_encode($setItem[$subkey]);

                $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
                $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '\/' => '/', '\n' => '', '<p>' => '', '</p>' => '', '<P>' => '', '</P>' => '', '<i>' => '', '</i>' => '', '<I>' => '', '</I>' => '', '<b>' => '', '</b>' => '', '<B>' => '', '</B>' => ''];
                $field = $key . "." . $subkey;
                $searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);
            }
        } else {
            $repositoryValue = array_key_exists($key, $rows) ? json_encode($key) . ': ' . $rows[$key] : '';

            $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
            $replaceList = ['{' => '', '}' => '', '[' => '', ']' => '', '\/' => '/', '\n' => '', '<p>' => '', '</p>' => '', '<P>' => '', '</P>' => '', '<i>' => '', '</i>' => '', '<I>' => '', '</I>' => '', '<b>' => '', '</b>' => '', '<B>' => '', '</B>' => ''];
            $searchResults[$key] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);
        }
    }
    return $searchResults;
}

function readCitationItem($repository, $itemId) {
    $rows = searchDataItem($repository, $itemId);
    $searchResults = [];

    foreach (array_keys($rows) as $key) {
        if (is_array($rows[$key])) {
            foreach (array_keys($rows[$key]) as $subkey) {
                $setItem = $rows[$key];
                $repositoryValue = json_encode($subkey) . ': ' . json_encode($setItem[$subkey]);
                $citationField = strtoupper(substr($subkey, 0, 4));

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
        }
    }
    return $searchResults;
}

function searchDataItem($repository, $itemId) {
    $input_array = [
        'esIndex' => $repository->index,
        'searchFields' => ['_id'],
        'query' => $itemId];

    $search = new ElasticSearch($input_array);
    $search->setSearchResult();

    $result = $search->getSearchResult();
    return $result['hits']['hits'][0]['_source'];
}
