<?php

require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/config/datasources.php';
require_once dirname(__FILE__) . '/search/Repositories.php';
require_once dirname(__FILE__) . '/search/ElasticSearch.php';

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$repositoryHolder = new Repositories();

if (isset($_POST['radio-share'])) {
    $shareType = $_POST['radio-share'];
    $sharedData = "";
    $selectedRows = explode(",", $_POST['selected-rows']);
    $newLine = $shareType == "file" ? "\r\n" : "<br />";
    $rowIndex = 0;

    foreach ($selectedRows as $rowString) {
        $repositoryId = str_replace('share-item-repository=', '', explode("&", $rowString)[0]);
        $itemId = str_replace('id=', '', explode("&", $rowString)[1]);
        $results = NULL;

        foreach ($repositoryHolder->getRepositories() as $repository) {
            if ($repository->id == $repositoryId) {
                $results = readItem($repository, $itemId);
                break;
            }
        }

        $sharedData .= '=== Row ' . ($rowIndex + 1) . " ===" . $newLine;

        foreach ($results as $field) {
            $sharedData .= $field . $newLine;
        }
        $sharedData .= $newLine;
        $rowIndex += 1;
    }

    // File
    if ($shareType == "file") {
        header('Content-Disposition: attachment; filename="biocaddie-share.txt"');
        header('Content-Type: text/plain');
        header('Content-Length: ' . strlen($sharedData));
        header('Connection: close');
        echo $sharedData;
    }
    // Email
    else {
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
            $repositoryValue = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : '';
        } else if (count($keys) == 2) {
            $setItem = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : [];
            $repositoryValue = array_key_exists($keys[1], $setItem) ? json_encode($keys[1]) . ': ' . json_encode($setItem[$keys[1]]) : '';
        } else if (count($keys) == 3) {
            $repositoryValue = $repository->id == "0008" ? $rows[$keys[0]][$keys[1]][0][$keys[2]] : $rows[$keys[0]][$keys[1]][$keys[2]];
//            $setItem = array_key_exists($keys[0], $rows) ? $rows[$keys[0]] : [];
//            $setItemSecondLevel = array_key_exists($keys[1], $setItem) ?
//                    $repository->id === "0008" ? $setItem[$keys[1]][0] : $setItem[$keys[1]] : [];
//
//            $repositoryValue = array_key_exists($keys[2], $setItemSecondLevel) ? json_encode($keys[2]) . ': ' . json_encode($setItemSecondLevel[keys[2]]) : '';
        }

        $displayValue = is_array($repositoryValue) ? json_encode($repositoryValue) : $repositoryValue;
        $replaceList = [ '{' => '', '}' => '', '[' => '', ']' => '', '"' => '', '\/' => '/'];
        $searchResults[$field] = str_replace(array_keys($replaceList), array_values($replaceList), $displayValue);
    }
    return $searchResults;
}

?>