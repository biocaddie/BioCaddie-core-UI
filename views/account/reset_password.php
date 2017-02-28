<div class="container" style="margin-top: 40px;margin-bottom: 150px">
    <div class="col-lg-4 col-lg-offset-4">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title"> Reset your password</h3>
                </div>
                <div class="panel-body">

                        <form action="./reset.php" method="POST">
                            <div class="form-group" style="margin-top: 10px">
                                <label>E-mail Address: </label>
                                <input class="form-control" type="text" name="email" size="20"/>
                            </div>
                            <div class="form-group" style="margin-top: 10px">
                                <label>New Password: </label>
                                <input class="form-control" type="password" name="password" size="20"/>
                            </div>
                            <div class="form-group" style="margin-top: 10px">
                                <label>Confirm Password:</label> <input class="form-control" type="password"
                                                                        name="confirmpassword" size="20"/>
                            </div>
                            <input type="hidden" name="q" value="<?php if (isset($_GET["q"])) {echo $_GET["q"];}?>"/>
                            <button type="submit" class="btn btn-primary" id="btn-recover"
                                    style="width: 100%;margin-bottom: 30px" name="ResetPasswordForm"
                                    value=" Reset Password  ">
                                Submit
                            </button>
                        </form>
            
                </div>

                <div class="panel-footer">
                    <div class="container">
                        <a class="account" href="login.php"> Go back to Sign In</a>
                    </div>
                </div>

            </div>
        </div>
    </div> <!--/.end of col-lg-4-->

</div> <!--/.end of container-->
