d3.csv("words.csv",function(error, data) {
    var fill = d3.scale.category20();
   
//console.log(rdata);

 d3.layout.cloud().size([180,180])
  .words(data.map(function(d) {return {text: d.text, size: d.size};}))
  //.words (data.forEach(function(d) {
         //       d.text = +d.text; //parseDate(d.year);
        //         d.size = +d.size;
         //        }))
  .rotate(function() { return 0; })
  .font("helvetica")
  .fontSize(function(d) { return d.size; })
  .on("end", draw)
  .start();


function draw(words) {
d3.select("body").select("#word-cloud").select("#svg-key")
    .append("g")
    .attr("transform", "translate(90,90)")
    .selectAll("text")
    .data(words)
    .enter().append("text")
    .style("font-size", function(d) { return d.size + "px"; })
    .style("font-family", function(d) { return d.font; })
    .attr("text-anchor", "middle")
    .classed("cloud", true)
    .attr("data-elems", function(d) {return d.text;})
    .attr("transform", function(d) {
      return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
    })
    .attr("id", function(d,i) { return "keycloud-"+i.toString(); })
    .text(function(d) { return d.text; })
    .on("mouseover", function() {
		d3.select(this).style("cursor", "pointer");
		})
    .on("click" , function () {
		   multi_select = false;
			if (d3.event != null)
					{
						 if (d3.event.ctrlKey || d3.event.metaKey) {
							 					$("#word-apply").show();
							 					$("#word-apply").prop( "disabled", false);
							 if (d3.select(this).attr('data-clicked')=='0')
									{
									
								d3.select(this)
									.attr('data-clicked', "1")
									.classed('barClicked', true);
									word_list[d3.select(this).attr('data-elems')]=0;
									}
								else
								{
									d3.select(this)
									.attr('data-clicked', "0")
									.classed('barClicked', false);
								delete word_list[d3.select(this).attr('data-elems')];
								}
							
							 var word_size = Object.keys(word_list).length; 
							 if (word_size == 0)
							 {
								 $("#word-apply").prop( "disabled", true);
								 $("#word-apply").hide();
							 }
							 multi_select = true;
						 }
						 else
						 {
							 multi_select = false;
						}
					}
			
			if (multi_select != true)
				{
					$("#word-apply").hide();
					$("#word-apply").prop( "disabled", true);
			
					if (d3.select(this).attr('data-clicked')=='0')
									{
								d3.selectAll(".cloud")
									.attr('data-clicked', "0")
									.classed('barClicked', false);
								word_list={};	
								d3.select(this)
									.attr('data-clicked', "1")
									.classed('barClicked', true);
								word_list[d3.select(this).attr('data-elems')]=0;
									}
								else
								{
									d3.selectAll(".cloud")
									.attr('data-clicked', "0")
									.classed('barClicked', false);
									word_list={};
								}
					 
				}
		  });
}
    
});
