
$("#search-form").submit(function () {
    if ($('input[name=searchtype]:checked').val() === 'repository') {
        $("#search-form").attr("action", "search.php");
        $("#repository").remove();
    }
    else {
        $("#search-form").attr("action", "search-repository.php");
    }
    return true;
});