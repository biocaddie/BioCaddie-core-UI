<?php

/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 7/12/16
 * Time: 4:17 PM
 */

class Pubmed {

    protected $pmid;
    protected $title;
    protected $source;
    protected $pubDate;

    public function setPmid($newPmid) {
        $this->pmid = $newPmid;
    }

    public function getPmid() {
        return $this->pmid;
    }

    public function setTitle($newTitle) {
        $this->title = $newTitle;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setSource($newSource) {
        $this->source = $newSource;
    }

    public function getSource() {
        return $this->source;
    }

    public function setPubDate($newPubDate) {
        $this->pubDate = $newPubDate;
    }

    public function getPubDate() {
        return $this->pubDate;
    }

}
