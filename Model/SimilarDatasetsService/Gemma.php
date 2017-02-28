<?php

error_reporting(0);
ini_set('display_errors', 0);

require_once dirname(__FILE__) . '/../../config/config.php';

class GemmaDataset {

    protected $gemmaId;

    /**
     * @return mixed
     */
    public function getGemmaId()
    {
        return $this->gemmaId;
    }

    /**
     * @param mixed $gemmaId
     */
    public function setGemmaId($gemmaId)
    {
        $this->gemmaId = $gemmaId;
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
    public function getGemmaUId()
    {
        return $this->gemmaUId;
    }

    /**
     * @param mixed $gemmaUId
     */
    public function setGemmaUId($gemmaUId)
    {
        $this->gemmaUId = $gemmaUId;
    }

    /**
     * @return mixed
     */
    public function getDataItemTypes()
    {
        return $this->dataItemTypes;
    }

    /**
     * @param mixed $dataItemTypes
     */
    public function setDataItemTypes($dataItemTypes)
    {
        $this->dataItemTypes = $dataItemTypes;
    }
    protected $dataItemTitle;
    protected $gemmaUId;
    protected $dataItemTypes;

}

class GemmaSimilarData {

    public function getGemmaDataset($gemmaid,$count) {

        // Get json file
        //$url = "http://localhost:8085/dataset%23" . $gemmaid;
        global $similarity_url;
        global $es_end_point;
        $url = $similarity_url . $gemmaid;
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
               // $dataUrl = "http://datamed.biocaddie.org:9200/gemma/" . $uid;
                $dataUrl = "http://".$es_end_point."/gemma/" . $uid;
                if (substr(php_uname(), 0, 7) == "Windows") {
                    $curlHandle = curl_init();
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curlHandle, CURLOPT_URL, $dataUrl);
                    $jsonPDB = curl_exec($curlHandle);
                    curl_close($curlHandle);
                } else {
                    $jsonPDB = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $dataUrl);
                }
                $dataGemma = json_decode($jsonPDB, true);
		
		if($dataGemma["found"]){	
			$gemmaDataset = new GemmaDataset();
			$gemmaDataset->setGemmaId($dataGemma["_source"]["dataItem"]["ID"]);
			$gemmaDataset->setGemmaUId($dataGemma["_id"]);
                	$gemmaDataset->setDataItemTitle($dataGemma["_source"]["dataset"]["title"]);
                	$gemmaDataset->setDataItemTypes($dataGemma["_source"]["dataset"]["types"]);
			array_push($pdbArray, $gemmaDataset);
            	}
	    }
        }
        return array_slice($pdbArray,1,$count);
    }

}

?>
