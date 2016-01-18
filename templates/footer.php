<div class="footer">
	<div class="container">

		<p class="muted text-center blackgray add_padding"> bioCADDIE is supported by the National Institutes of Health<a href="http://grants.nih.gov/grants/guide/rfa-files/RFA-HL-14-031.html" target="_blank"> </a>
			through the Big Data to Knowledge, Grant 1U24AI117966-01. 
			<a href="http://www.nih.gov/" target="_blank" class='white'>NIH | </a> 
			<a href="http://www.ucsd.edu/" target="_blank" class='white'>UCSD | </a>
			<a href="http://healthsciences.ucsd.edu/som/medicine/divisions/dbmi/pages/default.aspx" target="_blank" class="white">DBMI</a>
		</p>
	</div> <!-- container-->
</div> <!-- navbar navbar-default navbar-fixed-bottom" -->


<script language="javascript">
$(".comment").shorten({
	"showChars" : 50,
	"moreText"  : "more",
	"lessText"  : "less",
});

$(document).ready(function() {
	
	$(".comment").shorten();

	/*$(".btn-primary").click(function(){
        $(".collapse").collapse('toggle');
    });*/
});

$(".clickable").click(function() {

	$(this).nextUntil('.clickable').toggle();
	
});

</script>
</body>
</html>