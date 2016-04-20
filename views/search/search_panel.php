<?php

function partialSearchPanel($searchBuilder) {

        if ($searchBuilder->getSearchType() == 'repository') {
            $repo_status = 'checked';
            $data_status = 'unchecked';
        } else {
            $repo_status = 'unchecked';
            $data_status = 'checked';
        }

    ?>
    <div class="jumbotron search-block-2">
        <form id="search-form" action='search.php' method='get' autocomplete='off'>
            <div class="input-group">
                <input value="<?php echo $searchBuilder->getQuery(); ?>" type="text" class="form-control" placeholder="Search for data through BioCADDIE" name='query' id='query'>
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-warning" type='submit' value=''>
                        <i class="fa fa-search"></i>
                    </button>
                </div>

            </div>
            <div class="form-inline" style="padding: 7px 0;">
                <label class="radio-inline">
                    <input  name="searchtype" id="radio1" value="data" type="radio" <?php print $data_status; ?>>
                    <span class="search-text-md" style="line-height: 21px;">Search for data set</span>
                </label>
                <label class="radio-inline">
                    <input name="searchtype" id="radio2" value="repository" type="radio" <?php print $repo_status; ?>>
                    <span class="search-text-md" style="line-height: 21px;">Search for repository</span>
                </label>

                <a href="help.php" class="hyperlink pull-right" style="font-size: 12px">help</a>
                <a href="advanced.php" class="hyperlink pull-right" style="font-size: 12px;margin-right: 10px">Advanced Search</a>
            </div>
            <span id="search-example" class="search-text-sm "><strong>Search Examples:</strong> (Breast Cancer, Genetic Analysis Software, Gene EGFR, Lung[title] AND Cancer, Cancer AND (Lung[Title] OR Skin[Title]))</span>

        </form>
    </div>
    <?php
}
?>
