var timeChart = function(timeChartData, year){
	var count = Object.keys(timeChartData).length;

	data = timeChartData;
	var key_list = {};
	var dyears = [];
	var dfreq = [];

	data.forEach(function(d) {
		d.year = +d.year;
		d.frequency = +d.frequency;
		dyears.push(+d.year);
		dfreq.push(+d.frequency);
	});

	var total_years = (dyears[0] - dyears[dyears.length-1])+1;
	var start_year = dyears[dyears.length-1];
	var end_year =  dyears[0];


	for (k=start_year; k<=end_year; k++)
	{
		if( dyears.indexOf(k) === -1 )
		{
			data.push({year:k, frequency:0});
		}

	}


	var margin = {top: 4, right: 2, bottom: 3, left: 4},
		height = document.getElementById('time-line').offsetHeight - margin.top - margin.bottom,
		barWidth = 8;
	var svg_width = (barWidth * total_years)-document.getElementById('time-line').offsetWidth;

	if (svg_width < 0)
	{
		var add_years = Math.ceil(parseFloat(Math.abs(svg_width))/barWidth);
		for (m=(start_year-add_years-1); m<start_year; m++)
		{
			data.push({year:m, frequency:0});
		}
	}
	total_years = data.length;

	var width = (barWidth * total_years) - margin.left - margin.right;

	var x = d3.time.scale()
		.range([0,width]);

	var y = d3.scale.linear()
		.range([height, 0]);

	var tip = d3.tip()
		.attr('class', 'd3-tip')
		.offset([-10, 0])
		.html(function(d) {
			return "<strong>Year "+d.year+" = </strong> <span style='color:red'>" + d.frequency + "</span>";
		})

	var svg = d3.select("body").select("#time-line").select("#svg-obj")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
		.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	svg.call(tip);

	x.domain(d3.extent(data,function(d) {return d.year; }));
	y.domain([0, d3.max(data, function(d) { return d.frequency; })]);


	svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")

	svg.append("g")
		.attr("class", "y axis")
		.append("text")
		.attr("transform", "rotate(-90)")
		.attr("y", 6)
		.attr("dy", ".71em")
		.style("text-anchor", "end");


	svg.selectAll(".bar")
		.data(data)
		.enter()
		.append("a")
		.attr("xlink:href",function(d){
			if(year!=""){
				return document.referrer.slice(0,-10)+ "&year="+d.year;
			}else{
				return document.referrer+ "&year="+d.year;
			}})
		.attr("target","_parent")
		.append("rect")
		.attr("class", "bar")
		.attr("x", function(d) { return x(d.year) - (barWidth / 2); })
		.attr("width", barWidth)
		.attr("stroke", "#2eb8b8")
		.attr("y", function(d) { return y(d.frequency); })
		.attr("height", function(d) {
			if(d.frequency==0)
			{
				d3.select(this)
					.classed('bar', false);
				return 1; //or set to return height;
			}
			else
			{

				d3.select(this)
					.on("click" , function () {
						multi_select = false;
						if (d3.event != null)
						{
							/*Disable multi_select in version 1.5*/
							if (d3.event.ctrlKey || d3.event.metaKey) {
								$("#time-apply").show();
								$("#time-apply").prop( "disabled", false);
								if (d3.select(this).attr('data-clicked')=='0')
								{

									d3.select(this)
										.attr("fill", "red")
										.attr('data-clicked', "1")
										.classed('barClicked', true);
									key_list[d3.select(this).attr('data-year')]=d3.select(this).attr('data-frequency');
								}
								else
								{
									d3.select(this)
										.attr("fill", "#E0E0E0")
										.attr('data-clicked', "0")
										.classed('barClicked', false);
									delete key_list[d3.select(this).attr('data-year')];
								}

								var key_size = Object.keys(key_list).length;
								if (key_size == 0)
								{
									$("#time-apply").prop( "disabled", true);
									$("#time-apply").hide();
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
							$("#time-apply").hide();
							$("#time-apply").prop( "disabled", true);

							if (d3.select(this).attr('data-clicked')=='0')
							{
								d3.selectAll(".bar")
									.attr("fill", "#E0E0E0")
									.attr('data-clicked', "0")
									.classed('barClicked', false);
								key_list={};
								d3.select(this)
									.attr("fill", "red")
									.attr('data-clicked', "1")
									.classed('barClicked', true);
								key_list[d3.select(this).attr('data-year')]=d3.select(this).attr('data-frequency');
							}
							else
							{
								d3.selectAll(".bar")
									.attr("fill", "#E0E0E0")
									.attr('data-clicked', "0")
									.classed('barClicked', false);
								key_list={};
							}

						}
					});
				return height - y(d.frequency);
			}
		})
		.attr("data-frequency", function(d) {return d.frequency;})
		.attr("data-year", function(d) {return d.year;})
		.attr("data-clicked", "0")
		.attr("fill", "#E0E0E0")
		.classed('barClicked', function(d){
			if(year.indexOf(String(d.year))!=-1){
				return true;
			}
		})
		.on("mouseover", tip.show)
		.on("mouseout", tip.hide);

	$("#time-canvas").scrollLeft(800);
}
