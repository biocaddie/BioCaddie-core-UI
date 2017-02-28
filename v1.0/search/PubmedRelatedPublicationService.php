<?php

class Pubmed {

    protected $pmid;
    protected $title;
    protected $source;
    protected $pubDate;

    public function set_pmid($newPmid) {
        $this->pmid = $newPmid;
    }

    public function get_pmid() {
        return $this->pmid;
    }

    public function set_title($newTitle) {
        $this->title = $newTitle;
    }

    public function get_title() {
        return $this->title;
    }

    public function set_source($newSource) {
        $this->source = $newSource;
    }

    public function get_source() {
        return $this->source;
    }

    public function set_pubDate($newPubDate) {
        $this->pubDate = $newPubDate;
    }

    public function get_pubDate() {
        return $this->pubDate;
    }

}

class PubmedPublication {

    // Get publication details using pmid
    public function getPublication($query, $count) {
        $idArray = $this->get_pmid($query);
        $urlBase = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=";
        $pubmedArray = array();
        $i = 0;

        if (count($idArray) > 0) {
            foreach ($idArray as $id) {
                if ($i < $count) {
                    $url = $urlBase . $id;
                    $resultPage = file_get_contents($url);

                    // Parse returned page
                    $xmlArray = simplexml_load_string($resultPage)
                            or die("Error: SimpleXML cannot create object");
                    $pubmed = new Pubmed();
                    $pubmed->set_pmid($id);
                    $pubmed->set_title($xmlArray->DocSum->Item[5]);
                    $pubmed->set_source($xmlArray->DocSum->Item[2]);
                    $pubmed->set_pubDate($xmlArray->DocSum->Item[0]);

                    array_push($pubmedArray, $pubmed);
                }
                $i++;
            }
        }

        return $pubmedArray;
    }

    // Get a list of pmid
    private function get_pmid($query) {
        $url = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=" . urlencode($query);
        $url = str_replace("%26nbsp%3B","",$url);
        $resultXML = $this->curl_get_contents($url);

        // Parse returned xml
        $xmlArray = simplexml_load_string($resultXML)
                or die("Error: SimpleXML cannot create object");
        $idArray = $xmlArray->IdList->Id;
        return $idArray;
    }

    function curl_get_contents($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}

?>