<div class="panel panel-primary">
    <div class="panel-heading">
        <h4>Builder</h4>
    </div>

    <div class="panel-body">
        <div class="row" style="margin-bottom: 10px">
            <div class="col-lg-12">
                <div id="group1">
                    <div class="dropdown" style="visibility: hidden">
                        <button id="op1" type="button" class="btn btn-default dropdown-toggle inner1 opul1 op1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">AND <span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu inner1 opul1">
                            <li><a href="#">AND</a></li>
                            <li><a href="#">OR</a></li>
                            <li><a href="#">NOT</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle inner1 fieldul1" id="drop1"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">All Search Fields <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu inner1 fieldul1">
                            <li><a href="#">All Search Fields</a></li>
                            <li><a href="#">Title</a></li>
                            <li><a href="#">Author</a></li>
                            <li><a href="#">Description</a></li>
                        </ul>
                    </div>
                    <input class="input inner1" id="field1" type="text"
                           placeholder="Type something""/>
                </div>
            </div>
        </div><!-- /row -->

        <div class="form-group" style="margin-top: 20px;margin-bottom: 20px">
            <button type="button" id="btnAdd" class="btn btn-default add-more pull-right"><span
                    class="glyphicon glyphicon-plus"></span>Add Criteria
            </button>
        </div>

        <div class="form-inline form-group col-lg-offset-4">
            <label class="radio-inline">
                <input name="searchtype" id="radio1" value="data" type="radio" name="searchtype" checked>
                <p class="search-text-md">Search for data set</p>
            </label>
            <label class="radio-inline">
                <input name="searchtype" id="radio2" value="repository" type="radio" name="searchtype">
                <p class="search-text-md">Search for repository</p>
            </label>
        </div>
        <hr>

        <div class="form-group">
            <input class="form-control" id="query" name='query'
                   placeholder="Use the builder below to create your search ">
        </div>
        <button type="button" class="btn btn-default pull-right" id="btn-show">Generate query</button>
    </div><!--/.panel-body-->

    <div class="panel-footer" style="height: 60px">
        <div>
            <a class="hyperlink" href="help.php">Help</a>
            <button type="submit" class="btn btn-warning pull-right" id="btn-search">Search</button>
        </div>
    </div><!--/.panel-footer-->
</div><!--/.panel-->