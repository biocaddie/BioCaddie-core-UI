/*
 * Search Suggestion Service.
 * */

var $queryInput = $("#query");


$queryInput.popover({
    placement: 'bottom',
    trigger: 'manual',
    html: true,
    title: function () {
        return '<span class="fa fa-search"></span> <span></span>';
    }
});

var typeWatchOptions = {
    callback: callSuggestions,
    // wait: The number of milliseconds to wait after the the last key press before firing the callback
    wait: 500,
    // highlight: Highlights the element when it receives focus
    highlight: true,
    // allowSubmit: Allows a non-multiline element to be submitted (enter key) regardless of captureLength
    allowSubmit: true,
    // captureLength: Minimum # of characters necessary to fire the callback
    captureLength: 2
};

$queryInput.typeWatch(typeWatchOptions);

$queryInput.click(function () {
    var queryString = $queryInput.val();
    if (queryString.length !== 0 && $queryInput.next('div.popover:visible').length !== 0) {
        return;
    }


    callSuggestions(queryString);
});

function callSuggestions(queryString) {
    var searchType = document.querySelector('input[name="searchtype"]:checked').value;
  
    if ($('input[name="searchtype"]:checked').val() !== 'data')
        return;

    if (queryString.length === 0)
    {
        if ($queryInput.next('div.popover:visible').length !== 0) {
            $queryInput.popover('hide');
        }
        return;
    }

    $queryInput.parent().find('.input-group-addon').removeClass('hidden');

    var serializedData = $queryInput.serialize();

    loadSuggestions(serializedData, queryString,searchType);
}

function loadSuggestions(serializedData, queryString,searchType) {

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "ajax/SearchSuggestionService.php",
        data: serializedData,
        success: function (data) {
            var generatedHtml = generateSuggectionResults(queryString, data,searchType);
            if ($queryInput.next('div.popover:visible').length === 0) {
                $queryInput.popover('show');
            }
            $queryInput.next('div.popover:visible').find('div.popover-content').html(generatedHtml);
        },
        error: function (e) {
            console.log("Error Occured.", e);
        },
        complete: function () {
            $queryInput.parent().find('.input-group-addon').addClass('hidden');
        }
    });
}

function generateSuggectionResults(queryString, data,searchType) {
    var htmlValue = "<div class=\"row\">";
    var href = "search.php?query=" + queryString + "&searchtype=data";

    var arrayLength = data.length;
    for (var i = 0; i < arrayLength; i++) {
        var type = data[i];
        htmlValue += "<div class=\"col-lg-2 col-md-3 col-sm-5\">" +
                "<h5>" +
                "<a id=\"datatype_"+i+"\" href=\"" + href + "&datatypes=" + type['datatypes'] + "\">" +
                type['datatypes'] + " (" + type['typeNum'] + ")" +
                "</a>" +
                "</h5>";
        var repoLength = type ['repository'].length;
        for (var j = 0; j < repoLength; j++) {
            htmlValue += displayResult(queryString, type['repository'][j],j,searchType);
        }
        htmlValue += "</div>";
    }
    htmlValue += "</div>";
    return htmlValue;
}

function displayResult(query, item,j,searchType) {
    return "<h6><a id=\"repository_"+j+"\" class=\"repositoryLabel\" href=\"search-repository.php?query=" +
            query + "&repository=" + item["id"] + "&searchtype="+ searchType+"\">" + item["repoShowName"] + ' (' + item["num"] + ')</a></h6>';
}

$('body').bind('click', function (e) {
    if (!$(e.target).is($queryInput) && $(e.target).parents(".popover").length === 0) {
        if ($queryInput.next('div.popover:visible').length) {
            $queryInput.popover('hide');
        }
    }
});

// ##############################################

/*
 * For the bar chart on landing page
 * */
var categories = ['', 'Swiss-Prot ', 'ClinVar',  'BioProject', 'PDB','Dryad','OmicsDI', 'ArrayExpress','Dataverse'];
var dollars = [438182, 208560,  155850, 122339, 82837,78201, 68189,60303];
var colors = ['#3a87ad', '#3a87ad', '#3a87ad', '#3a87ad', '#3a87ad', '#3a87ad', '#3a87ad', '#3a87ad'];

var grid = d3.range(25).map(function (i) {
    return {'x1': 0, 'y1': 0, 'x2': 0, 'y2': 220};
});


var tickVals = grid.map(function (d, i) {
    if (i > 0) {
        return i*80000;

    }
    else if (i === 0) {
        return "0";
    }
});

var xscale = d3.scale.linear()
        .domain([10, 1100000])
        .range([0, 580]);

var yscale = d3.scale.linear()
        .domain([0, categories.length])
        .range([0, 225]);

var colorScale = d3.scale.quantize()
        .domain([0, categories.length])
        .range(colors);

var canvas = d3.select('#repositories-statistics')
        .append('svg')
        .attr({'width': 320, 'height': 255});

var grids = canvas.append('g')
        .attr('id', 'grid')
        .attr('transform', 'translate(120,10)')
        .selectAll('line')
        .data(grid)
        .enter()
        .append('line')
        .attr({'x1': function (d, i) {
                return i * 30;
            },
            'y1': function (d) {
                return d.y1;
            },
            'x2': function (d, i) {
                return i * 30;
            },
            'y2': function (d) {
                return d.y2;
            },
        })
        .style({'stroke': '#adadad', 'stroke-width': '1px'});

var xAxis = d3.svg.axis();
xAxis
        .orient('bottom')
        .scale(xscale)
        .tickFormat(d3.format(".1s"))
        .tickValues(tickVals)
        ;

var yAxis = d3.svg.axis();
yAxis
        .orient('left')
        .scale(yscale)
        .tickSize(2)
        .tickFormat(function (d, i) {
            return categories[i];
        })
        .tickValues(d3.range(11));

var y_xis = canvas.append('g')
        .attr("transform", "translate(90,0)")
        .attr('id', 'yaxis')
        .call(yAxis);

var x_xis = canvas.append('g')
        .attr("transform", "translate(90,220)")
        .attr('id', 'xaxis')
        .call(xAxis)
    .append("text")
        .style("text-anchor", "end")
        .attr("x", 150)
        .attr("y", 35)
        .attr("dx", ".51em")
        .text("Number of Datasets");


var chart = canvas.append('g')
        .attr("transform", "translate(90,0)")
        .attr('id', 'bars')
        .selectAll('rect')
        .data(dollars)
        .enter()
        .append('rect')
        .attr('height', 20)
        .attr({'x': 0, 'y': function (d, i) {
                return yscale(i) + 17;
            }})
        .style('fill', function (d, i) {
            return colorScale(i);
        })
        .attr('width', function (d) {
            return 0;
        });


var transit = d3.select("svg").selectAll("rect")
        .data(dollars)
        .transition()
        .duration(1000)
        .attr("width", function (d) {
            return xscale(d);
        });

var transitext = d3.select('#bars')
        .selectAll('text')
        .data(dollars)
        .enter()
        .append('text')
        .attr({'x': function (d) {
                if (xscale(d) > 180) {
                    return xscale(d) - 80;
                } else {
                    return xscale(d) + 5;
                }
            }, 'y': function (d, i) {
                return yscale(i) + 35;
            }})
        .text(function (d) {
            return d;
        })
        .style({'fill': function (d) {
                if (xscale(d) > 180) {
                    return "#fff";
                } else {
                    return "#000";
                }
            }, 'font-size': '14px'});



