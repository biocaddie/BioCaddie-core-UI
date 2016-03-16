<?php
function partialShare($postLink) { 
    $query = filter_input(INPUT_GET, "query", FILTER_SANITIZE_STRING);
    ?> 
<iframe id="download_xls" name="download_xls" width="0" height="0" scrolling="no" frameborder="0" hidden="hidden" ></iframe>
    <div data-toggle="tooltip" data-placement="left" title="Share Page Result.">
        <button  type="button" class="btn btn-default btn-sm" style="border-radius: 2px; padding: 3px 5px" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-share-alt" style="margin-right: 5px;"></i>Send To
        </button>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 lass="modal-title" id="myModalLabel">
                        <i class="fa fa-share-alt" style="margin-right: 5px;"></i>
                        Share Current Page Results</h4>
                </div>
                <form name="share-form" id="share-form" action="http://<?php echo $postLink; ?>" method="post" target="download_xls">
                    <div class="modal-body">
                        <p>How do you prefer to share current page results?</p>                    
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default btn-sm active">
                                <input type="radio" name="radio-share" value="file" id="radio-share-file" onchange="shareToggler(false);" autocomplete="off" checked=""> 
                                <i class="fa fa-file" style="margin-right: 5px;"></i>File
                            </label>
                            <label class="btn btn-default btn-sm">
                                <input type="radio" name="radio-share" value="email" id="radio-share-email" onchange="shareToggler(true);" autocomplete="off"> 
                                <i class="fa fa-envelope" style="margin-right: 5px;"></i>E-Mail
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
                                <hr style="margin: 10px 0 0">
                                <br />
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