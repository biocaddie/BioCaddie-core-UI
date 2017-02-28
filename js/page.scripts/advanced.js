var rowCount = 0;

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
            if(value.length>0){
                // Construct query for displaying
                operator = $("#op" + index).text();
                query = query + " " + operator + " " + "\"" + value + "\"[" + field + "]";
            }else{
                return query;
            }

        } else {
            // Construct query for displaying
            query = query + "\"" + value + "\"[" + field + "]";
        }
    });

    return query;
}

$(document).ready(function () {     
    /*** Add/remove criteria. ***/
    var groupNum = [1]; // track id of existing input group

    $(".add-more").click(function (e) {
        e.preventDefault();

        var addto = "#group" + groupNum[groupNum.length - 1];
        var addRemove = "#group" + groupNum[groupNum.length - 1];
        var next = groupNum[groupNum.length - 1] + 1;
        groupNum.push(next);

        var newOp = '<button type="button" class="btn btn-default dropdown-toggle inner' + next + ' opul' + next + '" id="op' + next + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">AND <span class="caret"></span></button> ' +
                    '<ul class="dropdown-menu inner' + next + ' opul' + next + '"> ' +
                        '<li><a>AND</a></li>' +
                        '<li><a>OR</a></li>' +
                        '<li><a>NOT</a></li></ul>';

        var newOper = $(newOp);
        var newDrop = '<button type="button" class="btn btn-default dropdown-toggle inner' + next + ' fieldul' + next + '" id="drop' + next + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Search Fields <span class="caret"></span> </button> ' +
                    '<ul class="dropdown-menu inner' + next + ' fieldul' + next + '"> ' +
                        '<li><a>All Search Fields</a></li> ' +
                        '<li><a>Title</a></li> ' +
                        '<li><a>Author</a></li> ' +
                        '<li><a>Description</a></li>'+
                        '<li><a>Disease</a></li></ul>';

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

    /*** Show selected text from dynamically generated dropdown menu***/
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
                url: 'ajax/whatsthis.php',
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

    /*steps*/

    // Step 1
    $("input[name='searchtype']").change(function(){
        $('#step1').removeClass("disabled");
        $('#step1').addClass("complete");

    });
    // Step 2
    $('#field1').on('keyup', function(){
        $('#step2').removeClass("disabled");
        $('#step2').addClass("complete");
        $('#btn-search').removeClass("disabled");
        $('#btn-show').removeClass("disabled");
    });

    // Step 3
    $('.accessibility').click(function(){
        $('#step3').removeClass("disabled");
        $('#step3').addClass("complete");
    });

    // Step 4
    $('#btn-show').click(function(){
        var query = contructQuery();
        $('#query').val(query);

        $('#step4').removeClass("disabled");
        $('#step4').addClass("complete");
    });

    // Step 5
    $('#btn-search').click(function(){
        var query = contructQuery();
        $('#query').val(query);

        $('#step4').removeClass("disabled");
        $('#step4').addClass("complete");

        $('#step5').removeClass("disabled");
        $('#step5').addClass("complete");
    });

});





