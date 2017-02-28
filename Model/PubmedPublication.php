<?php

require_once dirname(__FILE__) . '/Pubmed.php';

class PubmedPublication {

    /*
     * Get publication details using pmid
     * @return array(Pubmed)
     */
    public function getPublication($query, $count) {
        $idArray = $this->getPmid($query);
        $urlBase = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=";
        $pubmedArray = array();
        $i = 0;
        if (count($idArray) > 0) {
            foreach ($idArray as $id) {
                if ($i < $count) {
                    $url = $urlBase . $id;
                    $resultPage = file_get_contents($url);
                    // Parse returned page
                    $xmlArray = simplexml_load_string($resultPage);
                           // or die("Error: SimpleXML cannot create object");
                    $pubmed = new Pubmed();
                    $pubmed->setPmid($id);
                    $pubmed->setTitle($xmlArray->DocSum->Item[5]);
                    $pubmed->setSource($xmlArray->DocSum->Item[2]);
                    $pubmed->setPubDate($xmlArray->DocSum->Item[0]);

                    array_push($pubmedArray, $pubmed);
                }
                $i++;
            }
        }

        return $pubmedArray;
    }

    /*
     * Get a list of pmid
     */
    private function getPmid($query) {
        //$query='cancer';
        $url = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=" . urlencode($query);
        $url = str_replace("%26nbsp%3B","",$url);
        $resultXML = $this->curlGetContents($url);
        // Parse returned xml
        $xmlArray = simplexml_load_string($resultXML);
                //or die("Error: SimpleXML cannot create object");
        $idArray = $xmlArray->IdList->Id;
        return $idArray;
    }
    /*
     * using curl to get content from url
     * @return string
     */
    function curlGetContents($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
    /*
     * extract title info from ES result
     * @return string
     */
    public function extractQuery($result){
        if (isset($result['dataItem']['title'])) {
            $query = $result['dataItem']['title'];
        }
        elseif(isset($result['dataset']['title'])){
            $query = $result['dataset']['title'];
        }
        elseif(isset($result['Dataset']['briefTitle'])){
            $query = $result['Dataset']['briefTitle'];
        }
        else {
            $query = $result['title'];
        }

        $query = trim(strip_tags($query));
        return $query;
    }


}

?>