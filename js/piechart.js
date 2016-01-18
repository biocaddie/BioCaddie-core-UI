var width = 280,
    height = 280,
    radius = Math.min(width, height) / 2 -50;

var color = d3.scale.ordinal()
    .range(["#71B360", "#1F77B4", "#98DF8A", "#FF7F00", "#FF7Fa0", "#FFBB78", "#ff0000"]);

var arc = d3.svg.arc()
    .outerRadius(radius)
    .innerRadius(radius/2);

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) { return d.population; });

var svg = d3.select("#pieChart").append("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");



d3.csv("./index/data.csv", function(error, data) {

  data.forEach(function(d) {
    d.population = +d.population;
  });

  var g = svg.selectAll(".arc")
      .data(pie(data))
    .enter().append("g")
      .attr("class", "arc");

  g.append("path")
      .attr("d", arc)
      .style("fill", function(d) { return color(d.data.age); });

  var pos = d3.svg.arc().innerRadius(radius + 2).outerRadius(radius + 2); 

  // Get the angle on the arc and then rotate by -90 degrees
  var getAngle = function (d) {
      return (180 / Math.PI * (d.startAngle + d.endAngle) / 2 - 90);
  };

g.append("text")
      //.attr("transform", function(d) { return "translate(" + pos.centroid(d) + ")"; })
      .attr("transform", function(d) { 
            return "translate(" + pos.centroid(d) + ") " +
                    "rotate(" + getAngle(d) + ")"; }) 
      .attr("dy", ".35em")
      .style("text-anchor", "start")
      .text(function(d) { return d.data.age; });



});