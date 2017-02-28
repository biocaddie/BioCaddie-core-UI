<?php
// Import Repository Classes.
foreach (glob(dirname(__FILE__) . '/repository/*.php') as $filename) {
    if (is_file($filename)) {
        require_once $filename;
    }
}

class Repositories
{
    private $repositories;

    /*
     * Returns a list of all repository objects.
     */
    public function getRepositories()
    {
        return $this->repositories;
    }

    /*
     * Returns a repository instance by id.
     */
    public function getRepository($id)
    {
        foreach ($this->repositories as $repository) {
            if ($repository->id == $id) {
                return $repository;
            }
        }
    }

    private $searchFields;

    /*
     * Returns a unique list of available search fields in all datasets.
     */
    public function getSearchFields()
    {
        //return $this->searchFields;
        return ['_all'];
    }

    function __construct()
    {
        $this->repositories = [];
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, 'RepositoryBase')) {
                if($class=="RepoRepository"){
                    continue;
                }
                array_push($this->repositories, new $class);
            }
        }

        $this->searchFields = [];
        foreach ($this->repositories as $repository) {
            $this->searchFields = ['all'];

           /* foreach ($repository->searchFields as $field) {
                if (!in_array($field, $this->searchFields)) {
                    array_push($this->searchFields, $field);
                }
            }*/
        }
    }
}
?>
