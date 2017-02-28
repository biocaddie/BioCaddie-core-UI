<div class="container" style="margin-top: 40px;margin-bottom: 50px">
    <div class="col-lg-6 col-lg-offset-3">
        <div class="row">
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
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title"> Sign Up</h3>
                    </div>
                    <div class="panel-body" style="padding: 20px">

                            <form  method="post" id="register_form">

                                <div class="form-group" style="margin-top: 10px">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="txt_uemail"
                                           placeholder="E-mail" value="<?php echo isset($_POST['txt_uemail']) ? $_POST['txt_uemail'] : '' ?>">
                                </div>

                                <div class="form-group" style="margin-top: 10px">
                                    <label>Screen name</label>
                                    <input type="username" class="form-control" id="exampleInputUsername" name="txt_uname"
                                           placeholder="Screen name" value="<?php echo isset($_POST['txt_uname']) ? $_POST['txt_uname'] : '' ?>">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="txt_upass"
                                           placeholder="Password">
                                </div>

                                <div class="form-group">
                                    <label>Password (Confirm)</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="txt_ucpass"
                                           placeholder="Password">
                                </div>

                                <button type="submit" class="btn btn-primary" name="btn-signup" style="width: 100%;margin-bottom: 30px">
                                    Register
                                </button>
                            </form>
                    </div>

                    <div class="panel-footer">
                        <div class="container">
                            <a class="account" href="login.php"> Go back to Sign In</a>
                        </div>
                    </div>

                </div>

                <?php
            }
            else if(isset($_GET['joined']))
            {
                ?>

                <div id="myModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Participate in our Usability Study</h4>
                            </div>
                            <div class="modal-body">
                                    <p>Dear Users:</p>
                                    <p>We are conducting interviews as part of our Usability Testing study to increase our understanding of how our website is perceived and experienced by visitors like you. If you are interested to participate, please click on the button below. We greatly appreciate your time.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btn-parti" onclick="saveconsent('<?php echo $_SESSION['email']?>')">Participate!</button>
                                <button type="submit" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='login.php'>login</a> here
                </div>
                <?php
            }else{
                ?>

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title"> Sign Up</h3>
                    </div>
                    <div class="panel-body" style="padding: 20px">
                            <form  method="post" id="register_form">

                                <div class="form-group" style="margin-top: 10px">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="txt_uemail"
                                           placeholder="E-mail">
                                </div>

                                <div class="form-group" style="margin-top: 10px">
                                    <label>Screen name</label>
                                    <input type="username" class="form-control" id="exampleInputUsername" name="txt_uname"
                                           placeholder="Screen name">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="txt_upass"
                                           placeholder="Password">
                                </div>

                                <div class="form-group">
                                    <label>Password (Confirm)</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" name="txt_ucpass"
                                           placeholder="Password">
                                </div>

                                <button type="submit" class="btn btn-primary" name="btn-signup" style="width: 100%;margin-bottom: 30px">
                                    Register
                                </button>
                            </form>
                    </div>

                    <div class="panel-footer">
                        <div class="container">
                            <a class="account" href="login.php"> Go back to Sign In</a>
                        </div>
                    </div>

                </div>
                <?php
            }
            ?>

        </div>
    </div> <!--/.end of col-lg-4-->

</div> <!--/.end of container-->

<div id="thankyouModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Participate in our Usability Study</h4>
            </div>
            <div class="modal-body">
                <p>Dear Users:</p>
                <p>Thank you for participating our study. We will contact by email soon.</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default" data-dismiss="modal" aria-hidden="true">OK</button>
            </div>
        </div>
    </div>
</div>
