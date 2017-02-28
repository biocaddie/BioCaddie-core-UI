<?php

require_once dirname(__FILE__) . '/RepositoryBase.php';

class RepoRepository extends RepositoryBase {

    public $repoShowName = 'repository';
    public $searchFields = ['resource_name', 'resource_type', 'keyword', 'e_uid', 'abbrev', 'curationstatus', 'parent_organization', 'data_created', 'related_disease', 'species', 'supporting_agency', 'synonym', 'availability', 'uses', 'listedby', 'lists', 'relatedto'];
    public $index = 'repository';
    public $type = 'list';
    public $searchPageField = ['resource_name', 'resource_type', 'keyword', 'supporting_agency'];
    public $searchPageHeader = ['resource_name' => 'Resource Name',
        'resource_type' => 'Resource Type',
        'keyword' => 'Keyword',
        'supporting_agency' => 'Supporting Agency'];
    public $sort_field = 'resource_name';

}

?>
