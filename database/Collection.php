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
        $getCollectionId = $this->getCollectionId();
        $getDatasetUrl = $this->getDatasetUrl();
        $getDatasetTitle = $this->getDatasetTitle();
        $getCreateTime = $this->getCreateTime();
        $getDatasetDescription = $this->getDatasetDescription();
        $getRepository = $this->getRepository();

        try {
            $stmt = $dbconn->prepare("INSERT INTO collections(collection_id,dataset_url,dataset_title, create_time, dataset_description, repository)
                                                    VALUES (:collection_id,:dataset_url,:dataset_title,:create_time,:dataset_description,:repository)");
            $stmt->bindparam(":collection_id", $getCollectionId);
            $stmt->bindparam(":dataset_url", $getDatasetUrl);
            $stmt->bindparam(":dataset_title", $getDatasetTitle);
            $stmt->bindparam(":create_time", $getCreateTime);
            $stmt->bindparam(":dataset_description", $getDatasetDescription);
            $stmt->bindparam(":repository", $getRepository);
            $stmt->execute();

            return $stmt;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    public function queryCollectionItem($dbconn)
    {
        $getCollectionId = $this->getCollectionId();
        try {
            $stmt = $dbconn->prepare("SELECT * FROM collections WHERE collection_id = :collection_id ORDER BY create_time DESC");
            $stmt->bindparam(":collection_id",$getCollectionId);
            $stmt->execute();
            $result = $stmt ->fetchAll();

            $dbconn = null;
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteCollectionItem($dbconn)
    {
        $getCollectionItemId = $this->getCollectionItemId();
        try {
            $stmt = $dbconn->prepare("DELETE FROM collections WHERE collection_item_id= :collection_item_id");
            $stmt->bindparam(":collection_item_id",$getCollectionItemId);
            $stmt->execute();

            return true;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteAllCollectionItem($dbconn)
    {
        $getCollectionId = $this->getCollectionId();
        try {
            $stmt = $dbconn->prepare("DELETE FROM collections WHERE collection_id= :collection_id");
            $stmt->bindparam(":collection_id",$getCollectionId);
            $stmt->execute();

            return true;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function Is_valid_User($dbconn, $collection_item_id, $user_email){
        try{
            // Get the collection ID of this collection item ID
            $stmt = $dbconn -> prepare("SELECT collection_id from collections where collection_item_id = :collection_item_id");
            $stmt->bindparam(":collection_item_id",$collection_item_id);
            $stmt->execute();
            $result = $stmt ->fetchAll();

            $collection_id = $result[0];

            //Get the user email of the collection id
            $stmt = $dbconn -> prepare("SELECT user_email FROM biocaddie.user_collections WHERE collection_id = :collection_id");
            $stmt->bindparam(":collection_id",$collection_id[0]);
            $stmt->execute();
            $result = $stmt ->fetchAll();
            $email = $result[0][0];

            return $user_email == $email;
        }catch(PDOException $e){
            echo $e->getMessage();
        }

    }
}
