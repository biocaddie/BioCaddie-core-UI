<!-- Show Login if the OAuth Request URL is set -->
<div class="container" style="margin: 50px auto;">
    <div class="col-xs-3 visible-xs"></div> <!--Add offset for mobile device-->
    <div class="col-md-4 col-md-offset-4 col-sm-5  col-sm-offset-4 col-xs-6">

        <div class="row">
            <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    ?>
                    <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title"> Sign In</h3>
                </div>
                <div class="panel-body">
                    <div style="padding: 5px 15px;">
                        <form method="post">
                            <div class="form-group" style="margin-top: 10px">
                                <input type="email" class="form-control" id="exampleInputEmail1" name="txt_uemail" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="exampleInputPassword1" name="txt_password"
                                       placeholder="Password">
                            </div>

                            <button type="submit" class="btn btn-primary" name="btn-login" style="width: 100%">Submit</button>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Keep me signed in
                                </label>
                                <label style="float: right">
                                    <a class="account" href="forgot_password.php">Forgot Password?</a>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel-footer">
                        <label style="margin-bottom: 20px">
                            Need new account?
                        </label>
                        <button type="submit" class="btn btn-primary" style="float: right;"
                                onclick="location.href = 'register.php'">Sign Up
                        </button>
                </div>
            </div>
        </div>
    </div><!--/.end of col-lg-4-->
</div><!--/.end of container-->
<div class="col-md-offset-5" style="margin-bottom:30px"><h5>... or login with</h5></div>

<div class="box">
    <div>
        <img src="img/user.png" width="100px" size="100px" /><br/>
        <a class='login' href='<?php echo $authUrl; ?>'><img class='login' src="img/sign-in-with-google.png" width="250px" size="54px" /></a>
    </div>
</div>
<!-- Show User Profile otherwise-->
