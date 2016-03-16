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