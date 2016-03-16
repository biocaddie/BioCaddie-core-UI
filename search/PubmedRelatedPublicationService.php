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
        $resultXML = file_get_contents($url);

        // Parse returned xml
        $xmlArray = simplexml_load_string($resultXML)
                or die("Error: SimpleXML cannot create object");

        $numId = count($xmlArray->IdList->Id);
        $idArray = $xmlArray->IdList->Id;
        return $idArray;
    }

}

?>