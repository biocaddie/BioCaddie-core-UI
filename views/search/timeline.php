<!-- Time Line -->

<?php
function partialTimeLine($searchBuilder){
    $timeChartData = $searchBuilder->getAggByDate();

    $year =filter_input(INPUT_GET, "year", FILTER_SANITIZE_STRING);
    $year = json_encode(explode(",",$year));
?>
    <script src="js/timechart.js"></script>
    <script>

        $(window).load(function(){
            timeChart(<?php echo $timeChartData;?>, <?php echo $year;?>);
        })

        </script>
    <style>
        .bar {
            fill: #33cccc;
        }

        .bar:hover {
            fill: #666666;
        }

        .barClicked {
            fill : #ff6666 !important;
        }

        .barClicked:hover {
            fill : #ff6666 !important;
        }
    </style>

    <div class="panel panel-primary" id="time-line">
        <div class="panel-heading">
            <strong>Timeline</strong>
            <input type="button" value="Apply" id="time-apply" style="display: none" disabled>
        </div>

        <div class="panel-body" style="overflow-x:scroll" id="time-canvas">
            <?php
            if($timeChartData=="[]"){
                ?>
                <div class="container">
                    <p class="text-center" style="margin: 10px">
                        <i class="fa fa-bar-chart fa-5x" aria-hidden="true"></i><br>

                        <blockquote class="blockquote">
                    <i class="mb-2">Timeline chart is not available for this repository</i>
                    </blockquote>
                    </p>
                    </div>
            <?php
            }else{
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" id="svg-obj"></svg>
        </div>
    </div>

    <?php
    }
}
?>
