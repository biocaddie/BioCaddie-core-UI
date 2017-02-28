<?php

error_reporting(0);
ini_set('display_errors', 0);
require_once dirname(__FILE__) . '/../config/config.php';
require_once dirname(__FILE__) . '/../config/datasources.php';
class Dataset {

    protected $datasetID;

    /**
     * @return mixed
     */
    public function getDatasetID()
    {
        return $this->datasetID;
    }

    /**
     * @param mixed $datasetID
     */
    public function setDatasetID($datasetID)
    {
        $this->datasetID = $datasetID;
    }

    /**
     * @return mixed
     */
    public function getDatasetTitle()
    {
        return $this->datasetTitle;
    }

    /**
     * @param mixed $datasetTitle
     */
    public function setDatasetTitle($datasetTitle)
    {
        $this->datasetTitle = $datasetTitle;
    }
    protected $datasetTitle;
}

class SimilarData {

    public function getSimilarDataset($datasetID,$count,$repoID) {

        // Get json file
        global $similarity_url;
        global $es_end_point;

        $url = $similarity_url . $datasetID;
        if (substr(php_uname(), 0, 7) == "Windows") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $json = curl_exec($ch);
            curl_close($ch);
        } else {
            $json = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $url);
        }
        // Parse json object
        $data = json_decode($json, true);
        $num = count($data["nodes"]);

        // Get index name

        $es_index = getRepositoryIDMapping()[$repoID];

        // Push pdb id into an array
        $pdbArray = array();

        if ($num > 0) {
            for ($i = 0; $i < $num; $i++) {
                $uid = str_replace('#', '/', $data["nodes"][$i]["name"]);
                $dataUrl = "http://".$es_end_point."/".$es_index."/" . $uid;

                if (substr(php_uname(), 0, 7) == "Windows") {
                    $curlHandle = curl_init();
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curlHandle, CURLOPT_URL, $dataUrl);
                    $jsonPDB = curl_exec($curlHandle);
                    curl_close($curlHandle);
                } else {
                    $jsonPDB = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $dataUrl);
                }
                $dataInfo = json_decode($jsonPDB, true);
                $Dataset = new Dataset();
                $Dataset->setDatasetID($dataInfo["_id"]);
                $Dataset->setDatasetTitle($dataInfo["_source"]["dataset"]["title"]);
                array_push($pdbArray, $Dataset);
            }
        }

        return array_slice($pdbArray,1,$count);
    }

}

?>
