var dontPopup = false;

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $(".clickable").click(function () {
        $(this).nextUntil('.clickable').toggle();
    });

    var showChar = 300;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    $('.more').each(function () {
        var content = $(this).html();

        if (content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

            $(this).html(html);
        }
    });

    $(".morelink").click(function () {
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });

    $("#share-form").submit(function (event) {
        var selectedRows = '';
        $.each(Cookies.get(), function (index, row) {
            if (row.substring(0, 11) === 'share-item-') {
                selectedRows += row.trim() + ',';
            }
        });
        selectedRows = selectedRows.substr(0, selectedRows.length - 1);
        if (selectedRows === '')
        {
            alert('You have to select a record to share first.');
            event.preventDefault();
            return false;
        }
        else {
            var input = $("<input name='selected-rows'>").attr("type", "hidden").val(selectedRows);
            $('#share-form').append($(input));
            $('#myModal').modal('hide');
        }
    });

    $("input[name='share-check'][type='checkbox']").change(function () {
        if (this.checked) {
            Cookies.set($(this).val(), $(this).val());
        }
        else {
            Cookies.remove($(this).val());
        }
        updateSelectedSharedItems();
    });
    updateSelectedSharedItems();
    setPreselectedSharedItems();

    $('#share-clear').click(function () {
        $.each(Cookies.get(), function (index, row) {
            if (row.substring(0, 11) === 'share-item-') {
                Cookies.remove(row);
                $("input[name='share-check'][type='checkbox'][value='" + row + "']").prop('checked', false);
            }
        });
        updateSelectedSharedItems();
    });
});

function updateSelectedSharedItems() {
    var count = 0;
    $.each(Cookies.get(), function (index, row) {
        if (row.substring(0, 11) === 'share-item-') {
            count++;
        }
    });
    if (count === 0) {
        $('#share-btn').hide();
    }
    else {
        $('#share-btn').show();
        $('#share-qty').text(count);
    }
}

function setPreselectedSharedItems() {
    $.each(Cookies.get(), function (index, row) {
        if (row.substring(0, 11) === 'share-item-') {
            $("input[name='share-check'][type='checkbox'][value='" + row + "']").prop('checked', true);
        }
    });
}

$(window).bind('beforeunload', function () {
    setTimeout(function () {
        $.LoadingOverlay("show", {
            image: "",
            fontawesome: "fa fa-spinner fa-spin"
        });
        dontPopup = true;
    }, 3000);
});

function shareToggler(show) {
    if (show === true) {
        $("#emailOption").show();
        $("#EmailAddress").attr('required', 'required');
    } else {
        $("#EmailAddress").removeAttr('required');
        $("#emailOption").hide();
    }
}

function signOut() {
    console.log(gapi);

    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
    });

    window.location = "login.php";
}