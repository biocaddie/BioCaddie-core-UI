

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">
  <!--<script src="./js/bootstrap-dropdown.js"></script>  -->
  <title>bioCADDIE GWAS pilot project</title>


  <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css" />



  <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>



  <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <script src="./js/bootstrap-dropdown.js"></script>  
  <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

  <script type="text/javascript" src="https://viralpatel.net/blogs/demo/jquery/jquery.shorten.1.0.js"></script>


  <!-- Bootstrap core CSS -->
  <!--<link href="./css/bootstrap.min.css" rel="stylesheet">-->
  
  <!-- Custom styles for this template -->
  <!--<link href="jumbotron-narrow.css" rel="stylesheet">-->

  <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
  <!--[if lt IE 9]><script src="./js/ie8-responsive-file-warning.js"></script><![endif]-->
  <script src="./js/ie-emulation-modes-warning.js"></script>
  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="./js/ie10-viewport-bug-workaround.js"></script>



  <link href="../css/style.css" rel="stylesheet">
  

  <!--search suggestion-->

  <!--<script src="../js/partial-search.js"></script>-->
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>




      <body>
  <div class="container"> 
    <div class="row">
            <div class="col-md-2">
                     <a href="https://biocaddie.org"><img src="../img/biocaddie-logo-transparent.png" alt="Mountain View" class="header-logo"></a>
            </div>
          <div id="title-box" class="col-md-6">
            <h3>GWAS Finder</h3>
            <p style="font-size:14px">Search literatures for Genome-Wide Association Studies(GWAS)</p>
          </div>
    </div>
 </div><!--/.container-->



  <div class="header clearfix">
      <nav class="navbar navbar-inverse">
          <div class="container">
              <ul class="nav navbar-nav navbar-right">
                  <?php if (basename(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_STRING), '.php') != 'index'): ?>
                      <li role="presentation" class="active"><a href="../index.php">Home</a></li>
                  <?php endif; ?>
                  <li role="presentation" class="active"><a href="../about.php">About</a></li>
                  <!--<li role="presentation" class="active"><a href="https://biocaddie.org/contact" target='_blank'>Contact</a></li>-->

                  <li class="active">

                      <a href="../feedback.php" class="dropdown-toggle dropdown"  role="presentation" aria-haspopup="true" aria-expanded="false">
                          Feedback<span class="caret"></span>
                      </a>

                      <ul class="dropdown-menu dropdown">
                          <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Report an issue</a>
                              <ul class="dropdown-menu">
                                  <li><a href="https://github.com/biocaddie/prototype_issues/issues" target="_blank">GitHub</a></li>
                                  <li><a href="../about.php">Contact Us</a></li>
                              </ul>
                          </li>

                          <li><a href="../questionnaire.php" >Questionnaire</a></li>
                      </ul>
                  </li>
                  <li class="active"><a href="../submit_repository.php" ><span data-toggle="tooltip" data-placement="bottom" title="Get your repository indexed" >Submit<span></span></a></li>
                  <?php if(!isset($_SESSION['name'])){?>
                      <li role="presentation" class="active"><a href="../login.php" id="partial_login_a">Login</a></li>
                  <?php }else{?>
                      <li role="presentation" class="active"><a href="../login.php" id="partial_login_a">
                              <?php
                              echo "MyDataMed";
                              ?>
                          </a></li>
                      <li role="presentation" class="active"><a href='../login.php?logout'>Logout</a></li>
                  <?php }?>
              </ul>
          </div>
      </nav>
  </div>