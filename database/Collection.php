<?php

/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 5/2/16
 * Time: 10:14 AM
 */
class Collection
{
    private $collection_id;
    private $dataset_url;
    private $dataset_title;
    private $create_time;
    private $dataset_description;
    private $repository;
    private $collection_item_id;

    /**
     * @return mixed
     */
    public function getCollectionItemId()
    {
        return $this->collection_item_id;
    }

    /**
     * @param mixed $collection_item_id
     */
    public function setCollectionItemId($collection_item_id)
    {
        $this->collection_item_id = $collection_item_id;
    }

    /**
     * @return mixed
     */
    public function getCollectionId()
    {
        return $this->collection_id;
    }

    /**
     * @param mixed $collection_id
     */
    public function setCollectionId($collection_id)
    {
        $this->collection_id = $collection_id;
    }

    /**
     * @return mixed
     */
    public function getDatasetUrl()
    {
        return $this->dataset_url;
    }

    /**
     * @param mixed $dataset_url
     */
    public function setDatasetUrl($dataset_url)
    {
        $this->dataset_url = $dataset_url;
    }

    /**
     * @return mixed
     */
    public function getDatasetTitle()
    {
        return $this->dataset_title;
    }

    /**
     * @param mixed $dataset_title
     */
    public function setDatasetTitle($dataset_title)
    {
        $this->dataset_title = $dataset_title;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @param mixed $create_time
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;
    }

    /**
     * @return mixed
     */
    public function getDatasetDescription()
    {
        return $this->dataset_description;
    }

    /**
     * @param mixed $dataset_description
     */
    public function setDatasetDescription($dataset_description)
    {
        $this->dataset_description = $dataset_description;
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }


    public function AddCollectionItem($dbconn)
    {
        try {
            $stmt = $dbconn->prepare("INSERT INTO collections(collection_id,dataset_url,dataset_title, create_time, dataset_description, repository)
                                                    VALUES (:collection_id,:dataset_url,:dataset_title,:create_time,:dataset_description,:repository)");
            $stmt->bindparam(":collection_id", $this->getCollectionId());
            $stmt->bindparam(":dataset_url", $this->getDatasetUrl());
            $stmt->bindparam(":dataset_title", $this->getDatasetTitle());
            $stmt->bindparam(":create_time", $this->getCreateTime());
            $stmt->bindparam(":dataset_description", $this->getDatasetDescription());
            $stmt->bindparam(":repository", $this->getRepository());
            $stmt->execute();

            $dbconn = null;
            return $stmt;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    public function queryCollectionItem($dbconn)
    {
        try {
            $sql = "SELECT * FROM collections WHERE collection_id=" . "'" . $this->collection_id . "' ORDER BY create_time DESC";
            $result = $dbconn->query($sql);

            $dbconn = null;
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteCollectionItem($dbconn)
    {
        try {
            $sql = "DELETE FROM collections WHERE collection_item_id=" . $this->getCollectionItemId();
            $result = $dbconn->query($sql);
            $dbconn = null;
            return $result;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
