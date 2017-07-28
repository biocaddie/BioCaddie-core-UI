<?php

/*
 * Display search results on the search.php page
 *
 * input: an object of ConstructSearchView class
 * @param
 *      $this->searchResults: array(array(string))
 * */
require_once dirname(__FILE__) . '/../../config/config.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
use \Guzzle\Http\Client;

function partialResults($searchView)
{
    $searchResult = $searchView->getSearchResults();
    $selectrepo = $searchView->getRepositoryFilter();
    $duplicate_flag = false;

    $searchIDList = array();

    if ($selectrepo == null) {
        $duplicate_flag = true;
        $duplicate_index = 'arrayexpress,gemma';
    } else if ((array_key_exists('GEO', $selectrepo) & (array_key_exists('GEMMA', $selectrepo) || array_key_exists('ArrayExpress', $selectrepo)))) {
        $duplicate_flag = true;

        $duplicate_index = "";
        if (array_key_exists('GEMMA', $selectrepo)) {
            $duplicate_index = $duplicate_index . ',' . 'gemma';
        }
        if (array_key_exists('ArrayExpress', $selectrepo)) {
            $duplicate_index = $duplicate_index . ',' . 'arrayexpress';
        }

    }/*
    if($duplicate_flag){
        $searchView->setSearchResults_without_duplicate();
        $searchResult = $searchView->getSearchResults_without_duplicate();

    }*/
    /*
     * This logic part should be moved to ConstructSearchView in the future
     * */
    if ($searchView->getSearchBuilder()->getSelectedTotalRows() > 0) {
        $singlePageUrlExtension = '&query=' . $searchView->getSearchBuilder()->getQuery();
        $selectedDatatypes = $searchView->getSearchBuilder()->getSelectedDatatypes();
        if ($selectedDatatypes != NULL) {
            $singlePageUrlExtension .= '&datatypes=' . implode(",", $selectedDatatypes);
        }

        ?>
        <ol class="search-result" id="search-result">
            <?php
            foreach ($searchResult as $item) {
                $primary_result=Null;
                $duplicate_results=Null;
                $primary_results_repositoryHits=0;
                $duplicate_results_repositoryHits=0;
                $keys = array_keys($item);
                $rowTitle = reduce_duplicate_in_title($item[$keys[0]]);
                $maxLen = 150;
                $rowTitleTooltip = strlen($rowTitle) > $maxLen ? $rowTitle : '';
                $start = strpos($rowTitle,'<strong1>');
                $rowTitleShort = $searchView->process_strong_highlight(strlen(strip_tags($rowTitle)) > $maxLen ? substr($rowTitle, max($start-50,0), $start+$maxLen) . '...' : $rowTitle);

                $linkUrl = $item['ref'];
                if ($searchView->getSearchBuilder()->getSearchType() != 'repository') {
                    $linkUrl = $item['ref'] . $singlePageUrlExtension;
                }

                ?>
                <li>
                    <p class="result-heading" data-html="true" title="<?php echo strip_tags($rowTitleTooltip) ?>"
                       data-toggle="tooltip" data-placement="bottom">
                        <!--checkbox-->
                        <input name="share-check" type="checkbox" value="<?php echo $item['ref_raw'] ?>"/>
                        <!--title-->
                        <?php array_push($searchIDList,$item['es_id']); ?>
                        <a id="<?php echo "result_" . $item['es_id']; ?>" href="<?php echo $linkUrl ?>">
                            <?php echo $rowTitleShort ?>
                        </a>
                        <!--repository label-->
                        <?php if ($searchView->getSearchBuilder()->getSearchType() == 'data'): ?>
                            <span class="result-reposity label label-repo">
                                <a href="search-repository.php?query=<?php echo $searchView->getSearchBuilder()->getQuery(); ?>&repository=<?php echo $item['source_ref']; ?>">
                                    <?php echo $item['source']; ?>
                                </a>
                            </span>
                        <?php endif; ?>

                        <?php if ($item['source'] == 'GEO' and $duplicate_flag): ?>
                        <?php
                        $gseID = $item['ID'];

                        $gseID = str_replace("<strong>", "", $gseID);
                        $gseID = str_replace("</strong>", "", $gseID);

                        $duplicate_search = new DuplicateSearch();
                        $duplicate_search->setSecondaryDatasets(['esIndex' => $duplicate_index, 'GSEID' => $gseID]);
                        $duplicate_results = $duplicate_search->getSearchResult();
                        $duplicate_results_repositoryHits = $duplicate_results['hits']['total'];
                        if ($duplicate_results_repositoryHits > 0):
                        ?>
                            <span  data-original-title="Is primary dataset"
                               data-toggle="tooltip" data-placement="right">
                                <i class="glyphicon glyphicon-open-file"></i>
                            </span>
                    <?php endif; ?>


                    <?php endif; ?>

                    <?php if (($item['source'] == 'GEMMA' or $item['source'] == 'ArrayExpress') and $duplicate_flag): ?>
                        <?php
                        $search = new DuplicateSearch();
                        $primary_result = $search->getPrimaryResult(strtolower($item['source']), $item['es_id']);

                        $primary_results_repositoryHits = $primary_result['hits']['total'];

                        if ($primary_results_repositoryHits > 0):?>
                            <span  data-original-title="Is secondary dataset"
                                   data-toggle="tooltip" data-placement="right">
                                <i class="glyphicon glyphicon-copy"></i>
                            </span>

                        <?php endif; ?>
                    <?php endif; ?>





                    <!--<?php if (($item['source'] == 'GEMMA' or $item['source'] == 'ArrayExpress') and $duplicate_flag): ?>
                        <?php
                        $search = new DuplicateSearch();
                        $link = $search->getPrimaryLinkFromID(strtolower($item['source']), $item['es_id']);
                        if ($link):
                            ?>
                            <a href="<?php echo $link ?>" title="" data-original-title="Is secondary dataset"
                               data-toggle="tooltip" data-placement="right">
                                <i class="glyphicon glyphicon-open-file"></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>-->
                    </p>

                    <!--fields-->
                    <?php
                    foreach (array_slice($keys, 1, sizeof($keys) - 6) as $key) {

                        if ($key != "ref") {
                            $start = strpos(trim($item[$key]),'<strong1>');
                            $fieldDisplayValue = strlen(trim($item[$key])) > 350 ? substr(trim($item[$key]), 0, 350) . '... (More In Details)' : trim($item[$key]);
                            $fieldTitleTooltip = strlen(trim($item[$key])) > $maxLen ? trim($fieldDisplayValue) : '';
                            $fieldTitleShort = $searchView->process_strong_highlight(strlen(trim($item[$key])) > $maxLen ? substr(trim($item[$key]), max($start-50,0), $start+ $maxLen) . '...' : trim($item[$key]));

                            ?>
                            <p class="result-field">
                                <em><?php echo trim($key) ?>:</em>
                            <span title="" data-original-title="<?php echo strip_tags($fieldTitleTooltip) ?>"
                                  data-toggle="tooltip" data-placement="right">
                                <?php echo $fieldTitleShort ?>
                            </span>
                            </p>
                        <?php }

                    } ?>

                    <?php  if($item['snippet']!=Null):?>
                        <?php $key = array_keys($item['snippet'])[0];?>
                        <em><?php echo $key;?>:</em>
                        <?php echo $item['snippet'][$key];?>
                    <?php endif;?>

                    <?php if ($item['source'] == 'GEO' and $duplicate_flag and $duplicate_results): ?>
                        <?php
                        $gseID = $item['ID'];
                        $gseID = str_replace("<strong1>", "", $gseID);
                        $gseID = str_replace("</strong1>", "", $gseID);

                        //$duplicate_search = new DuplicateSearch();
                        //$duplicate_search->setSecondaryDatasets(['esIndex' => $duplicate_index, 'GSEID' => $gseID]);
                        //$duplicate_results = $duplicate_search->getSearchResult();
                        //$duplicate_results_repositoryHits = $duplicate_results['hits']['total'];
                        if ($duplicate_results_repositoryHits > 0):
                            ?>
                            <a class="btn btn-primary btn-sm" role="button" data-target="#collapse<?php echo $gseID ?>"
                               data-toggle="collapse"
                               aria-expanded="true" aria-controls="collapse<?php echo $gseID ?>">
                                <i class="fa fa-expand"></i> View secondary datasets </a>
                            <div id="collapse<?php echo $gseID ?>" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <?php duplicateResults($searchView->getSearchResultsinFormat($duplicate_results), $searchView); ?>

                            </div>
                        <?php endif; ?>


                    <?php endif; ?>

                    <?php if (($item['source'] == 'GEMMA' or $item['source'] == 'ArrayExpress') and $duplicate_flag and $primary_result): ?>
                        <?php
                        //$search = new DuplicateSearch();
                        //$primary_result = $search->getPrimaryResult(strtolower($item['source']), $item['es_id']);

                        //$primary_results_repositoryHits = $primary_result['hits']['total'];

                        if ($primary_results_repositoryHits > 0):?>
                            <br>
                        <a class="btn btn-primary btn-sm" role="button" data-target="#collapse<?php echo $item['es_id'] ?>"
                           data-toggle="collapse"
                           aria-expanded="true" aria-controls="collapse<?php echo $item['es_id']?>">
                            <i class="fa fa-expand"></i> View primary dataset </a>
                        <div id="collapse<?php echo $item['es_id'] ?>" class="panel-collapse collapse" role="tabpanel"
                             aria-labelledby="headingOne">
                            <?php duplicateResults($searchView->getSearchResultsinFormat($primary_result), $searchView); ?>

                        </div>

                        <?php endif; ?>
                    <?php endif; ?>

                </li>
                <?php
            }
            ?>
        </ol>
        <?php
        if (isset(getallheaders()['Cookie'])) {
            $headerCookies = explode("; ", getallheaders()['Cookie']);
            $cookies = array();
            foreach ($headerCookies as $item) {
                list($key, $val) = explode('=', $item, 2);
                $cookies[$key] = $val;
            }

            $logstash_array = array(
                "cookieID" => $cookies['utest'],
                "actionTime" => "",
                "actionType" => "server",
                "actionID" => "",
                "actionText" => "",
                "actionX" => "",
                "actionY" => "",
                "actionURL" => "",
                "searchQuery" => $searchView->getSearchBuilder()->getQuery(),
                "searchTotal" => $searchView->getSearchBuilder()->getTotalRows(),
                "searchCurrent" => $searchView->getSearchBuilder()->getSelectedTotalRows(),
                "searchType" => $searchView->getSearchBuilder()->getSearchType(),
                "searchRepoSel" => $searchView->getSearchBuilder()->getSelectedRepositories(),
                "searchRepoFacets" => json_encode($searchView->getRepositoryFilter()),
                "searchDataSelect" => $searchView->getSearchBuilder()->getSelectedDatatypes(),
                "searchDataTypes" => json_encode($searchView->getDataTypeFilter()),
                "searchResults" => $searchIDList
            );


            global $logstash_url;
            $client = new Client();

            try {
                $request = $client->post($logstash_url);
                $request->setBody(json_encode($logstash_array), 'application/json');
                $request->setHeader('Content-Type', 'application/json');
                $client->send($request);
            } catch (Exception $e) {

            }
        }
    } else {
        require_once dirname(__FILE__) . '/../no_item_found.php';
    }
}

?>


