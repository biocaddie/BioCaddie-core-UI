function search() {
    if ($('input[name=searchtype]:checked').val() == 'data') {
        var query = $("#query").val();
        if (query.length === 0) {
            $('#dialog').dialog('close');
            return;
        }
        var data = $("#query").serialize();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "ajax/SearchSuggestionService.php",
            data: data,
            success: function (data) {
                var text = "<div class=\"row\">";
                var href = "search.php?query=" + query+"&searchtype=data";
                var j = 1;
                for (var type in data['datatypes']) {
                    text += "<div class=\"col-md-6\">" +
                        "<h5>" +
                        "<a href=\"" + href + "&datatypes=" + type + "\">" + type + " (" + data['datatypes'][type] + ")</a>" +
                        "</h5>";
                    switch (type) {
                        case "Protein Structure":
                            text += displayResult(query, data['repository'][1]);
                            break;
                        case "Phenotype":
                            text += displayResult(query, data['repository'][0]);
                            text += displayResult(query, data['repository'][16]); //MPD
                            break;
                        case "Gene Expression":
                            for (var i = 2; i < 6; i++) {
                                text += displayResult(query, data['repository'][i]);
                            }
                            text += displayResult(query, data['repository'][19]);//NURSA
                            break;
                        case "Nucleotide Sequence":
                            text += displayResult(query, data['repository'][6]);
                            break;
                        case "Unspecified":
                            text += displayResult(query, data['repository'][7]);
                            text += displayResult(query, data['repository'][9]);
                            text += displayResult(query, data['repository'][11]);
                            text += displayResult(query, data['repository'][17]);//Niddkcr
                            break;
                        case "Clinical Trials":
                            text += displayResult(query, data['repository'][8]);
                            text += displayResult(query, data['repository'][14]); // ctn
                            break;
                        case "Imaging Data":
                            text += displayResult(query, data['repository'][10]);
                            text += displayResult(query, data['repository'][12]); // neuromorpho
                            text += displayResult(query, data['repository'][14]); // ctn
                            text += displayResult(query, data['repository'][15]); // Cia
                            text += displayResult(query, data['repository'][18]); // openFMRI
                            break;
                        case "Morphology":
                            text += displayResult(query, data['repository'][12]); //neuromorpho
                            break;
                        case "Proteomics Data":
                            text += displayResult(query, data['repository'][13]);
                            text += displayResult(query, data['repository'][21]);
                            break;
                        case "Physiological Signals":
                            text += displayResult(query, data['repository'][20]);
                            text += displayResult(query, data['repository'][22]); //YPED

                            break;
                    }
                    text += "</div>";
                    j++;
                }
                text += "</div>";
                $dialog = $("#dialog");
                $dialog.dialog({
                    autoOpen: false,
                    width: 600,
                    height: 400,
                    dialogClass: 'noTitleStuff'
                });
                $dialog.html(text);
                $dialog.dialog("open");

                $(".ui-dialog").position({
                    my: "left top",
                    at: "left bottom",
                    of: $("#query")
                });

                $dialog.position({
                    my: "center bottom",
                    at: "center bottom",
                    of: $(".ui-dialog")
                });

                $("#query").focus();
            },
            error: function (e) {
                console.log("Error: " + e.responseText);
            }
        });
    }
}

function displayResult(query, item) {
    return "<h6><a class=\"repositoryLabel\" href=\"search-repository.php?query="
        + query + "&repository=" + item["id"] + "\">" + item['show_name'] + ' (' + item["num"] + ')</a></h6>';
}


(function () {
    var timer = null;
    var lastVal='';

    var haystack =[];

    $("#query").on('keyup', function (e) {
        if (timer) {
            clearTimeout(timer);
        }
        if(dontPopup){
           return;
        }
        timer = setTimeout(search, 100);
        
        var data = $("#query").val();
	if(data==lastVal){
	return;
	}else{
	   lastVal=data;
	}



        /*Autocomplete*/

        /*
        $.ajax({
            url: 'ajax/whatsthis.php',
            data: {q: data},
            dataType: "json",
            success: function (data) {
                    haystack.length=0;
		    $.map(data, function (item) {
                    haystack.push(item.completion);
                });
            },
            complete: function () {
                $('#loading').hide();

 var e = $.Event("keyup.suggest");
                e.which = 8;
                $('#query').trigger(e);
		}
        });*/
    });

    /*
	$(function(){
     $('#query').suggest(haystack, {});
        });
*/

})();


$('body').bind('click', function (e) {
    $dialog = $('#dialog');
    if ($dialog.hasClass('ui-dialog-content') && $dialog.dialog('isOpen')
        && !$(e.target).is('.ui-dialog, a')
        && !$(e.target).closest('.ui-dialog').length) {
        $dialog.dialog('close');
    }
});

var categories= ['','BioProject', 'PDB', 'GEO', 'Dryad','ArrayExpress', 'Dataverse', 'SRA','NeuroMorpho'];

var dollars = [155851,113495,105035,67456,63925,60304,43089,34083];

var colors = ['#3a87ad','#3a87ad','#3a87ad','#3a87ad','#3a87ad','#3a87ad','#3a87ad','#3a87ad'];

var grid = d3.range(25).map(function(i){
    return {'x1':0,'y1':0,'x2':0,'y2':220};
});

var grid = d3.range(25).map(function(i){
    return {'x1':0,'y1':0,'x2':0,'y2':220};
});

var tickVals = grid.map(function(d,i){
    if(i>0){ return i*80000; }
    else if(i===0){ return "0";}
});

var xscale = d3.scale.linear()
    .domain([10,500000])
    .range([0,722]);

var yscale = d3.scale.linear()
    .domain([0,categories.length])
    .range([0,225]);

var colorScale = d3.scale.quantize()
    .domain([0,categories.length])
    .range(colors);

var canvas = d3.select('#repositories-statistics')
    .append('svg')
    .attr({'width':350,'height':250});

var grids = canvas.append('g')
    .attr('id','grid')
    .attr('transform','translate(150,10)')
    .selectAll('line')
    .data(grid)
    .enter()
    .append('line')
    .attr({'x1':function(d,i){ return i*30; },
        'y1':function(d){ return d.y1; },
        'x2':function(d,i){ return i*30; },
        'y2':function(d){ return d.y2; },
    })
    .style({'stroke':'#adadad','stroke-width':'1px'});

var	xAxis = d3.svg.axis();
xAxis
    .orient('bottom')
    .scale(xscale)
    .tickValues(tickVals);

var	yAxis = d3.svg.axis();
yAxis
    .orient('left')
    .scale(yscale)
    .tickSize(2)
    .tickFormat(function(d,i){ return categories[i]; })
    .tickValues(d3.range(11));

var y_xis = canvas.append('g')
    .attr("transform", "translate(90,0)")
    .attr('id','yaxis')
    .call(yAxis);

var x_xis = canvas.append('g')
    .attr("transform", "translate(90,220)")
    .attr('id','xaxis')
    .call(xAxis);

var chart = canvas.append('g')
    .attr("transform", "translate(90,0)")
    .attr('id','bars')
    .selectAll('rect')
    .data(dollars)
    .enter()
    .append('rect')
    .attr('height',20)
    .attr({'x':0,'y':function(d,i){ return yscale(i)+17; }})
    .style('fill',function(d,i){ return colorScale(i); })
    .attr('width',function(d){ return 0; });


var transit = d3.select("svg").selectAll("rect")
    .data(dollars)
    .transition()
    .duration(1000)
    .attr("width", function(d) {return xscale(d); });

var transitext = d3.select('#bars')
    .selectAll('text')
    .data(dollars)
    .enter()
    .append('text')
    .attr({'x':function(d) { if(xscale(d)>100){return xscale(d)-80;}else{return xscale(d)+5;} },'y':function(d,i){ return yscale(i)+35; }})
    .text(function(d){ return d; })
    .style({'fill':function(d){if(xscale(d)>100){return "#fff";}else{return "#000";}},'font-size':'14px'});


// Update text on search panel on radio button click
$('input[name=searchtype]').change(function () {
    switch ($('input[name=searchtype]:checked').val()) {
        case 'data':
            $('#query').attr('placeholder', 'Search for data through bioCADDIE');
            $('#search-example').html('<strong>Search Examples:</strong> (Breast Cancer, Genetic Analysis Software, Gene EGFR, Lung[title] AND Cancer, Cancer AND (Lung[Title] OR Skin[Title]))');
            break;
        case 'repository':
            $('#query').attr('placeholder', 'Search for repository through bioCADDIE');
            $('#search-example').html('<strong>Search Examples:</strong> (Gene expression, Cancer)');
            break;
        default:
            $('#query').attr('placeholder', 'Search for data through bioCADDIE');
            $('#search-example').html('<strong>Search Examples:</strong> (Breast Cancer, Genetic Analysis Software, Gene EGFR, Lung[title] AND Cancer, Cancer AND (Lung[Title] OR Skin[Title]))');
    }
});
