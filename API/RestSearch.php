<?php
require_once dirname(__FILE__) . '/Rest.php';
require_once dirname(__FILE__) . '/array2xml.php';
require_once dirname(__FILE__) . './../Model/SearchBuilder.php';

class RestSearch extends Rest
{
    public function __construct()
    {
        parent::__construct();
        switch ($this->method) {
            case 'DELETE':
            case 'POST':
            case 'PUT':
            case 'GET':
                $this->getSearchResult();
                break;
            default:
                $this->setHttpHeaders($this->requestContentType, 405);
                break;
        }
    }

    public function getSearchResult()
    {

        $searchBuilder = new SearchBuilder();
        $searchBuilder->apiFlag=true;
        $searchBuilder->searchSelectedRepo();
        $rawData = $searchBuilder->getElasticSearchResults()['hits'];
        if ($rawData==NULL) {
            $statusCode = 404;
            $rawData = array('error' => 'No result found!');
        } else {
            $statusCode = 200;
        }
        $result = ["total"=>$rawData['total'],
                   'offset'=>$searchBuilder->getOffset(),
                   'rowsPerPage'=>$searchBuilder->getRowsPerPage(),
                   'hits'=>$rawData['hits']
        ];
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
        if (strpos($requestContentType, '*/*') !== false) {
            $response = json_encode($result);
            echo $response;
        }
        else if (strpos($requestContentType, 'application/json') !== false) {
            $response = json_encode($result);
            echo $response;
        }
        else if (strpos($requestContentType, 'text/html') !== false) {
            $response = json_encode($result);
            echo $response;
        } else if (strpos($requestContentType, 'application/xml') !== false) {
            $xml = Array2XML::createXML('results', $result);
            $response = $xml->saveXML();
            echo $response;
        }

    }

}

$search = new RestSearch();
?>
