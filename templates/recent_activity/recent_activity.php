<div class="panel panel-success" id="recent">
	<div class="panel-heading">Recent Activity</div>
	<div class="panel-body">
		<div class="table-responsive ">
			<table class="table table-default">
				<tbody>
					<?php   
					$hisArray=$_SESSION["history"];
					$num = count($hisArray);

					if($num>5){
						$ct = 0;
						$lastNArray=array_slice($hisArray, -5,5);

						for($i=4; $i>-1;$i--)
						{
							$ct = $ct+1;
							echo "<tr>
							<td>
							<span class=\"glyphicon glyphicon-search\"></span><a href=\"./datasource.php?query=".$lastNArray[$i]."\">".$lastNArray[$i].'</a>
							</td>
							</td>';
						}

					}else{
						for($j=$num-1; $j>-1;$j--) {
							echo "<tr>
							<td>
							<span class=\"glyphicon glyphicon-search\"></span><a href=\"./datasource.php?query=".$hisArray[$j]."\">".$hisArray[$j].'</a>
							</td>
							</td>';
						}

					}


					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
