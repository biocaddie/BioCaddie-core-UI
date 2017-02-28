
<div class="jumbotron search-block">
    <h4 style="margin-bottom: 10px">Engaging The Community Toward a Data Discovery Index (v1.0)</h4>
    <form action='./search.php' method='get' autocomplete='off' id="search-form" style="line-height: normal">
        <div class="input-group">
            <input type="text" class="form-control" autocomplete='off' placeholder="Search for data through bioCADDIE" name='query' id='query' autofocus="">
            <div class="input-group-btn">
                <button class="btn btn-warning" type='submit' value='submit' style="height: 34px">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <div class="form-inline" style="line-height: 0;margin-top: 17px">
            <label class="radio-inline">
                <input name="searchtype" id="radio1" value="data" type="radio" checked>
                <span class="search-text-md">Search for data set</span>
            </label>
            <label class="radio-inline">
                <input name="searchtype" id="radio2" value="repository" type="radio">
                <span class="search-text-md">Search for repository</span>
            </label>

            <a href="advanced.php" class="hyperlink pull-right" style="font-size: 12px;margin-right: 30px">Advanced Search</a>
            <a href="help.php" class="hyperlink pull-right" style="font-size: 12px">help</a>
        </div>
        <span id="search-example" class="search-text-sm "><strong>Search Examples:</strong> (Breast Cancer, Genetic Analysis Software, Gene EGFR, Lung[title] AND Cancer, Cancer AND (Lung[Title] OR Skin[Title]))</span>


    </form>

    <div id="dialog"></div>
</div>