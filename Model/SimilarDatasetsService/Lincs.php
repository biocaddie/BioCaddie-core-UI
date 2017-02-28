<?php

error_reporting(0);
ini_set('display_errors', 0);
require_once dirname(__FILE__) . '/../../config/config.php';
class LINCSDataset {

    protected $lincsId;
    protected $dataItemTitle;
    protected $dataResourceName;
    protected $cellName;

    /**
     * @return mixed
     */
    public function getLincsId()
    {
        return $this->lincsId;
    }

    /**
     * @param mixed $lincsId
     */
    public function setLincsId($lincsId)
    {
        $this->lincsId = $lincsId;
    }

    /**
     * @return mixed
     */
    public function getDataItemTitle()
    {
        return $this->dataItemTitle;
    }

    /**
     * @param mixed $dataItemTitle
     */
    public function setDataItemTitle($dataItemTitle)
    {
        $this->dataItemTitle = $dataItemTitle;
    }

    /**
     * @return mixed
     */
    public function getDataResourceName()
    {
        return $this->dataResourceName;
    }

    /**
     * @param mixed $dataResourceName
     */
    public function setDataResourceName($dataResourceName)
    {
        $this->dataResourceName = $dataResourceName;
    }

    /**
     * @return mixed
     */
    public function getCellName()
    {
        return $this->cellName;
    }

    /**
     * @param mixed $cellName
     */
    public function setCellName($cellName)
    {
        $this->cellName = $cellName;
    }



}

class LINCSSimilarData {

    public function getLincsDataset($lincsid,$count) {

        // Get json file
        global $similarity_url;
        global $es_end_point;
        //$url = "http://localhost:8085/dataset%23" . $lincsid;
        $url = $similarity_url . $lincsid;
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

        // Push pdb id into an array
        $pdbArray = array();

        if ($num > 0) {
            for ($i = 0; $i < $num; $i++) {
                $uid = str_replace('#', '/', $data["nodes"][$i]["name"]);
		//$dataUrl = "http://datamed.biocaddie.org:9200/lincs/" . $uid;
        $dataUrl = "http://".$es_end_point."/lincs/" . $uid;
		if (substr(php_uname(), 0, 7) == "Windows") {
                    $curlHandle = curl_init();
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curlHandle, CURLOPT_URL, $dataUrl);
                    $jsonPDB = curl_exec($curlHandle);
                    curl_close($curlHandle);
                } else {
                    $jsonPDB = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $dataUrl);
                }
                $dataLincs = json_decode($jsonPDB, true);
                $lincsDataset = new LINCSDataset();
                $lincsDataset->setLincsId($dataLincs["_id"]);
                $lincsDataset->setDataItemTitle($dataLincs["_source"]["dataset"]["title"]);
                array_push($pdbArray, $lincsDataset);
	   }
        }
        return array_slice($pdbArray,1,$count);
    }

}

?>
