<div class="container" style="margin-top: 80px">
    <div class="col-lg-10 col-lg-offset-1">

        <?php
        if(isset($error))
        {
        foreach($error as $error)
        {
            ?>
            <div class="alert alert-danger">
                <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
            </div>
            <?php
        }
        }
        ?>


            <form method="post" action = "collections.php">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title"> What would you like to do?</h3>
                </div>
                <div class="panel-body" style="margin:30px; height: 150px">
                    <div class="container">
                            <div class="row vdivide">
                                <div class="col-md-6">
                                <div class="radio">
                                    <label><input type="radio" name="collectionsradio" value="new" checked="checked">Create new collection</label>
                                </div>

                                    <div class="form-group" id = "newName">
                                        <label for="usr">Enter a name for your collection:</label>
                                        <input type="text"  id="collectionNmae" name = "collectionName">
                                    </div>
                                    </div>

                                <div class="col-md-6">
                                <div class="radio">
                                    <label><input type="radio" name="collectionsradio" value = "existing">Append to an existing collection</label>
                                </div>

                                    <div class="form-group" id = "existingList" hidden>
                                        <label for="usr">Choose a collection:</label>
                                        <div class="dropdown" >
                                            <select name ="selectName">
                                                <?php
                                                    foreach($collectionNames as $name){
                                                        echo '<option value = "'.$name['collection_name'].'">'.$name['collection_name'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    </div>

                            </div>

                    </div>
                </div>

                <div class="panel-footer">

                        <button type="submit" class="btn btn-primary" style="float: right"
                                onclick="location.href='../../register.php'">Submit
                        </button>
                    <div class="clearfix"></div>
                    </div>

            </div> <!--/panel-->

            </form>
    </div><!--/.end of col-lg-10-->
</div><!--/container-->