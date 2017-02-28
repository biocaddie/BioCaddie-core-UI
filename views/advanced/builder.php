<div class="panel panel-primary">
    <div class="panel-heading">
        <h4>Builder</h4>
    </div>

    <div class="panel-body">
        <div class="container">

            <div class="row bs-wizard " style="border-bottom:0;margin-bottom: 80px">

                <div class="col-xs-2 col-sm-offset-1 bs-wizard-step complete" id="step1"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum">Step 1</div>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center">Search for data set/repository</div>
                </div>


                <div class="col-xs-2  bs-wizard-step disabled" id="step2">
                    <div class="text-center bs-wizard-stepnum">Step 2</div>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center">Add search criteria</div>
                </div>


                <div class="col-xs-2 bs-wizard-step disabled" id="step3"><!-- complete -->
                    <div class="text-center bs-wizard-stepnum">Step 3 (Optional)</div>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center">Choose accessibility</div>
                </div>


                <div class="col-xs-2 bs-wizard-step disabled" id="step4"><!-- active -->
                    <div class="text-center bs-wizard-stepnum">Step 4(Optional)</div>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center">Generate/Edit query</div>
                </div>

                <div class="col-xs-2 bs-wizard-step disabled" id="step5"><!-- active -->
                    <div class="text-center bs-wizard-stepnum">Step 5</div>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="bs-wizard-info text-center">Search!</div>
                </div>
            </div>
        </div>


    </div>

    <div class="step step1"
         style="padding-top: 20px">
        <span class="label label-pill label-warning">Step 1</span>
        <div class="row">
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
        </div>
    </div>


    <div class="step step2"
         style="padding-bottom: 50px">
        <span class="label label-pill label-warning">Step 2</span>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-lg-12">
                <div id="group1">
                    <div class="dropdown" style="visibility: hidden">
                        <button id="op1" type="button" class="btn btn-default dropdown-toggle inner1 opul1 op1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">AND <span
                                class="caret"></span>
                        </button>
                        <ul class="dropdown-menu inner1 opul1">
                            <li><a>AND</a></li>
                            <li><a>OR</a></li>
                            <li><a>NOT</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle inner1 fieldul1" id="drop1"
                                data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">All Search Fields <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu inner1 fieldul1">
                            <li><a>All Search Fields</a></li>
                            <li><a>Title</a></li>
                            <li><a>Author</a></li>
                            <li><a>Description</a></li>
                            <li><a>Disease</a></li>
                        </ul>
                    </div>
                    <input class="input inner1" id="field1" type="text"
                           placeholder="Type something""/>
                </div>
            </div>
        </div><!-- /row -->

        <div class="form-group pull-right" style="margin-bottom: 20px">
            <button type="button" id="btnAdd" class="btn btn-default add-more "><span
                    class="glyphicon glyphicon-plus"></span>Add Criteria
            </button>
        </div>
    </div> <!--/ step 1-->




    <div class="step step3"
         style="margin-top: 50px">
        <span class="label label-pill label-warning">Step 3</span> (Optional)
        <div class="form-inline form-group col-lg-offset-2">
            <span>Accessibility of the Repository:  </span>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default accessibility">
                    <input type="radio" name="access" id="option1" autocomplete="off" value="download"> Download
                </label>

                <label class="btn btn-default accessibility">
                    <input type="radio" name="access" id="option3" autocomplete="off" value ="remoteService"> Remote Service
                </label>

                <label class="btn btn-default accessibility disabled">
                    <input type="radio" name="access" id="option2" autocomplete="off" value="remoteAccess"> Remote Access
                </label>

                <label class="btn btn-default accessibility disabled">
                    <input type="radio" name="access" id="option4" autocomplete="off" value="enclave"> Enclave
                </label>
                <label class="btn btn-default accessibility disabled">
                    <input type="radio" name="access" id="option5" autocomplete="off" value="notAvailable"> Not Available
                </label>
            </div>
        </div>
    </div>
    <hr>

    <div class="step step4"
         style=" padding-bottom: 50px;">
        <span class="label label-pill label-warning">Step 4</span> (Optional)
        <div class="form-group" style="margin-top: 10px">
            <input class="form-control" id="query" name='query'
                   placeholder="">
        </div>
        <button type="button" class="btn btn-default pull-right disabled" id="btn-show">Generate/Edit query</button>
    </div>
</div><!--/.panel-body-->

<div class="panel-footer" style="height: 60px; margin-bottom: 50px">
    <div>
        <a class="hyperlink" href="help.php">Help</a>
        <button type="submit" class="btn btn-warning pull-right disabled" id="btn-search">Search</button>
    </div>
</div><!--/.panel-footer-->
</div><!--/.panel-->