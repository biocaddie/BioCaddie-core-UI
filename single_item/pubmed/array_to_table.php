 
<div class="panel panel-warning" id="pub">
  <div class="panel-heading">
    <h3 class="panel-title">Find Related Publication</h3>
  </div>
  <div class="panel-body">
    <div class="table-responsive "> 
	<table class="table table-default">
		<thead>
		</thead>
		<tbody>
			<?php foreach ($itemArray as $item):?>
			<tr>
				<td>
					<div class="publication sidebar">
						<a href=<?php echo "http://www.ncbi.nlm.nih.gov/pubmed/".$item -> get_pmid();?>>
							<?php 
							$str = $item -> get_title();
								echo substr($str,0,65);
							?>
						</a>
						<p class= "pubmed"> <?php echo "["
						.$item -> get_source().", "
						.$item-> get_pubDate()."]";?>
						</p>
					</div>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>
  </div>
</div>