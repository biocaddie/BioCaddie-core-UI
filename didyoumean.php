<?php

$has_fix = false;
$q = '';

if(isset($_REQUEST['q'])) {
    $q = $_REQUEST['q'];
    $url = '129.106.31.121:9200/words/_suggest';
    $data = array(
      "suggest" => array(
        "text" => "lung cancre thyroid",
        "term" => array(
            "analyzer" => "standard",
            "field" =>  "term"
        )
      )
    );

    $data['suggest']['text'] = $q;
    $data = json_encode($data);

    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec( $ch );
    $response = json_decode($response);
    $response = $response->suggest;

    $fixed_ver = [];
    foreach($response as $sugg) {
        if(count($sugg->options) > 0) {
            $has_fix = true;
            $fixed_ver[] = $sugg->options[0]->text ;
        }
        else {
           $fixed_ver[] = $sugg->text ;
        }
    }
}
/*
$data = '{
  "my-suggestion": {
    "text": "lung cancre thyroid",
    "term": {
        "analyzer" : "standard",
      "field" :  "term"
    }
  }
}';
*/

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>BioCADDIE</title>

    <!-- Bootstrap core CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/readable/bootstrap.min.css" rel="stylesheet" integrity="sha384-/x/+iIbAU4qS3UecK7KIjLZdUilKKCjUFVdwFx8ba7S/WvxbrYeQuKEt0n/HWqTx" crossorigin="anonymous">
  </head>

  <body>

    <div class="container" style="margin-top:60px">

    <form role="form">
        <div class="input-group">
        <input type="search" id="searchText" class="form-control" placeholder="Search something!" name="q" value="<?php echo $q; ?>">
            <span class="input-group-btn">
                <button class="btn btn-secondary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </span>
        </div>
        <div>
            <?php
            if(isset($_REQUEST['q'])) {
                echo "<p style='padding-top:20px;'>Results for <b>".$_REQUEST['q'] . "</b></p>";
            }
            if($has_fix) {
                $q = implode($fixed_ver, ' ');
                echo "<p><b>Did you mean</b> <a style='font-size:18px' href='?q=$q'>" . $q . '</a>?</p>';
            }
            ?>
        </div>
    </form>
    </div><!-- /.container -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>
