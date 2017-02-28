<?php

error_reporting(0);
ini_set('display_errors', 0);
require_once dirname(__FILE__) . '/../../config/config.php';
class PDBDataset {

    protected $pdbid;
    protected $structureTitle;
    protected $experimentalTechnique;

    public function set_pdbid($newPdbid) {
        $this->pdbid = $newPdbid;
    }

    public function get_pdbid() {
        return $this->pdbid;
    }

    public function set_structureTitle($newStructureTitle) {
        $this->structureTitle = $newStructureTitle;
    }

    public function get_structureTitle() {
        return $this->structureTitle;
    }

    public function set_experimentalTechnique($newExperimentalTechnique) {
        $this->experimentalTechnique = $newExperimentalTechnique;
    }

    public function get_experimentalTechnique() {
        return $this->experimentalTechnique;
    }

}

class PDBSimilarData {

    public function getPDBDataset($itemId, $count) {
        $idArray = $this->get_pdbId(strtolower($itemId));
        // Convert array to string
        $pdbidString = implode(",", $idArray);
        $href = "http://www.rcsb.org/pdb/rest/customReport.xml?pdbids=" . $pdbidString . "&customReportColumns=structureId,structureTitle,experimentalTechnique";
        $pdbArray = array();

        // Get dataset details
        $resultXML = file_get_contents($href);

        // Parse xml
        $dataset = simplexml_load_string($resultXML);
        $json = json_encode($dataset);
        $array = json_decode($json, TRUE);
        $i = 0;


        if (array_key_exists("record", $array) && count($array["record"]) > 0) {
            foreach ($array["record"] as $record) {

                if ($i < $count && $i > 0) {
                    // Add dataset into to PDBDataset object
                    $pdbDataset = new PDBDataset();
                    $pdbDataset->set_pdbid($record["dimStructure.structureId"]);
                    $pdbDataset->set_structureTitle($record["dimStructure.structureTitle"]);
                    $pdbDataset->set_experimentalTechnique($record["dimStructure.experimentalTechnique"]);
                    array_push($pdbArray, $pdbDataset);
                }
                $i++;
            }
        }
        return $pdbArray;
    }

    private function get_pdbId($pdbid) {
        global $similarity_url;
        global $es_end_point;
        //$url = "http://localhost:8085/dataset%23" . $pdbid;
        $url = $similarity_url. $pdbid;
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
        $pdbidArray = array();

        if ($num > 0) {
            for ($i = 0; $i < $num; $i++) {
                $uid = str_replace('#', '/', $data["nodes"][$i]["name"]);
                $dataUrl = "http://".$es_end_point."/pdb/" . $uid;
                if (substr(php_uname(), 0, 7) == "Windows") {
                    $curlHandle = curl_init();
                    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curlHandle, CURLOPT_URL, $dataUrl);
                    $jsonPDB = curl_exec($curlHandle);
                    curl_close($curlHandle);
                } else {
                    $jsonPDB = exec("curl -H \"Connection: Keep-Alive\" -XGET " . $dataUrl);
                }
                $dataPDB = json_decode($jsonPDB, true);
                array_push($pdbidArray, explode(":",$dataPDB['_source']['dataset']['ID'])[1]);
            }
        }
        return $pdbidArray;
    }

}

?>
