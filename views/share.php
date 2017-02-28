<?php

function partialShare($postLink) {
    $query = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);
    ?> 
    <iframe id="download_xls" name="download_xls" width="0" height="0" scrolling="no" frameborder="0" hidden="hidden" ></iframe>
    <div data-toggle="tooltip" data-placement="left" title="Share Results.">
        <button id="share-btn" type="button" class="btn btn-default btn-sm" style="border-radius: 2px; padding: 3px 5px;" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-share-alt" style="margin-right: 5px;"></i>
            <span name="share-qty" class="badge" style="font-size: 10px;"></span>
            Send To
        </button>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" style="max-width: 450px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 lass="modal-title" id="myModalLabel">
                        <i class="fa fa-share-alt" style="margin-right: 5px;"></i>
                        Share Search Results
                    </h4>
                </div>
                <form name="share-form" id="share-form" action="share.php?<?php echo $_SERVER['QUERY_STRING'] ?>" method="post" target="download_xls">
                    <input type="hidden" name="query" value="<?php echo $query; ?>" />
                    <div class="modal-body">
                        <p id="lbl-share-all">You haven't selected any dataset. So all of the results for this query will be exported.</p>
                        <p id="lbl-share-some">You haven selected few datasets to export.</p>
                        <p><strong>How do you prefer to share them?</strong></p>
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default btn-sm active">
                                <input type="radio" name="radio-share" value="file" id="radio-share-file" onchange="shareToggler(false);" autocomplete="off" checked=""> 
                                <i class="fa fa-file" style="margin-right: 5px;"></i>File
                            </label>
                            <label class="btn btn-default btn-sm">
                                <input type="radio" name="radio-share" value="email" id="radio-share-email" onchange="shareToggler(true);" autocomplete="off"> 
                                <i class="fa fa-envelope" style="margin-right: 5px;"></i>E-Mail
                            </label>
                            <label id="collection-share" class="btn btn-default btn-sm">
                                <input type="radio" name="radio-share" value="collections" id="radio-share-collections" onchange="shareToggler(false);" autocomplete="off">
                                <i class="fa fa-database" style="margin-right: 5px;"></i>Collections
                            </label>
                            <label class="btn btn-default btn-sm">
                                <input type="radio" name="radio-share" value="citation" id="radio-share-citation" onchange="shareToggler(false);" autocomplete="off">
                                <i class="fa fa-book" style="margin-right: 5px;"></i>Citation Manager
                            </label>
                        </div>

                        <div class="option">                            
                            <p class="hidden" style="margin: 5px 0"><strong>File Format:</strong></p>
                            <div id="file-format" class="btn-group hidden" data-toggle="buttons">
                                <label class="btn btn-default btn-xs">
                                    <input type="radio" name="radio-file-format" value="summary" id="radio-file-format-summary" autocomplete="off"> 
                                    Summary (Text)
                                </label>                                
                                <label class="btn btn-default btn-xs">
                                    <input type="radio" name="radio-file-format" value="json" id="radio-file-format-json" autocomplete="off"> 
                                    JSON
                                </label>
                                <label class="btn btn-default btn-xs">
                                    <input type="radio" name="radio-file-format" value="csv" id="radio-file-format-csv" autocomplete="off"> 
                                    CSV
                                </label>
                            </div>

                            <div id="emailOption" class="chidden">
                                <hr style="margin: 10px 0">
                                <div class="form-group">
                                    <label for="EmailAddress">Email Address:</label>
                                    <input type="email" class="form-control input-sm" id="EmailAddress" name="EmailAddress" placeholder="Your Email Address...">
                                </div>

                                <div class="form-group">
                                    <label for="EmailSubject">Subject:</label>
                                    <input type="text" class="form-control input-sm" id="EmailSubject" name="EmailSubject" placeholder="Prefered Email Subject..." value="bioCaddie Exported Data">
                                </div>

                                <div class="form-group" style="margin-bottom: 0">
                                    <label for="EmailBody">Extra Comment:</label>
                                    <textarea id="EmailComment" name="EmailBody" class="form-control input-sm" rows="3" placeholder="Notes You May Want To Add To The Email Body...">bioCaddie data extracted for "<?php echo $query; ?>".</textarea>
                                </div>
                            </div>
                        </div>                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="share-clear">
                            <i class="fa fa-trash"></i>
                            Clear Selected Items
                        </button>
                        
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                            <i class="fa fa-remove"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-check"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>