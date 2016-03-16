var rowCount = 0;

function advancedSearch(query) {
    var data = query;

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "search/AdvancedSearchService.php",
        data: {"data": data},
        success: function (data, status) {
            rowCount++;
            //var totalNum = data.num;
            var newRow = jQuery('<tr><td>#' + rowCount + '</td><td>Search <strong>' + query + '</strong></td><td>'+ data.num +'</td><td>'+data.time+'</td></tr>');
            $('table.history').append(newRow);    // Add query to history table
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    });
}
function contructQuery(){

    var query = "";

    var count = $("div[id*='group']").length;

    var value = "";
    var field = "";
    var operator = "";



    $("div[id*='group']").each(function (index) {
        index = index + 1;
        value = $.trim($("#field" + index).val());
        field = $.trim($("#drop" + index).text());

        if (index > 1) {
            // Construct query for displaying
            operator = $("#op" + index).text();
            query = query + " " + operator + " " + "\"" + value + "\"[" + field + "]";
        } else {
            // Construct query for displaying
            query = query + "\"" + value + "\"[" + field + "]";
        }
    });

    return query;
}

$(document).ready(function () {

    /*** Add/remove criteria ***/
    var groupNum = [1]; // track id of existing input group

    $(".add-more").click(function (e) {
        e.preventDefault();

        var addto = "#group" + groupNum[groupNum.length - 1];
        var addRemove = "#group" + groupNum[groupNum.length - 1];
        var next = groupNum[groupNum.length - 1] + 1;
        groupNum.push(next);

        var newOp = '<button type="button" class="btn btn-default dropdown-toggle inner' + next + ' opul' + next + '" id="op' + next + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">AND <span class="caret"></span></button> ' +
                    '<ul class="dropdown-menu inner' + next + ' opul' + next + '"> <li><a href="#">AND</a></li><li><a href="#">OR</a></li><li><a href="#">NOT</a></li> </ul>';
        var newOper = $(newOp);
        var newDrop = '<button type="button" class="btn btn-default dropdown-toggle inner' + next + ' fieldul' + next + '" id="drop' + next + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Search Fields <span class="caret"></span> </button> ' +
                    '<ul class="dropdown-menu inner' + next + ' fieldul' + next + '"> <li><a href="#">All Search Fields</a></li> <li><a href="#">Title</a></li> <li><a href="#">Author</a></li> <li><a href="#">Description</a></li></ul>';
        var newDropDown = $(newDrop);
        var newIn = '<input autocomplete="off" class="input inner' + next + '" id="field' + next + '" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + next + '" class="btn btn-danger remove-me inner' + next + '" >-</button>';
        var removeButton = $(removeBtn);

        $(addRemove).after(removeButton);
        $(addto).after(newInput);
        $(addto).after(newDropDown);
        $(addto).after(newOper);

        var div = '<div id="group' + next + '"></div>';
        $(".inner" + next).wrapAll(div);

        var div = '<div class="dropdown"></div>';
        $(".opul" + next).wrapAll(div);
        $(".fieldul" + next).wrapAll(div);

        $("#field" + next).attr('data-source', $(addto).attr('data-source'));


        $('.remove-me').unbind().click(function (e) {
            e.preventDefault();
            var fieldNum = parseInt(this.id.match(/\d+/));
            var groupID = "#group" + fieldNum;
            groupNum.splice(groupNum.indexOf(fieldNum), 1);
            $(groupID).remove();

        });
    });

    /*** dynamically generated dropdown menus show selected text***/
    $(document).on('click', '.dropdown-menu li a', function () {
        var selText = $(this).text() + " ";
        $(this).parents('.dropdown').find('.dropdown-toggle').html(selText + '<span class="caret"></span>');
        $(this).focus();
    });


    /*** autocomplete function for input fields ***/
    $('[id^="field"]').autocomplete({
        source: function (req, res) {
            // $('#loading').show();
            $.ajax({
                url: 'whatsthis.php',
                data: {q: req.term},
                dataType: "json",
                success: function (data) {
                    res($.map(data, function (item) {
                        return {
                            label: item.completion,
                            value: item.completion
                        }
                    }));
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        }
    });


    /*** Save Search button onclick***/
    $('#btn-save').click(function () {
        var searchType = document.querySelector('input[name="searchtype"]:checked').value;
        var query = "(" + searchType + ")"+contructQuery();
         advancedSearch(query);

    });



    $('#btn-search').click(function(){
        var query = contructQuery();
        $('#query').val(query);
    });

    $('#btn-show').click(function(){
        var query = contructQuery();
        $('#query').val(query);
    });
});





