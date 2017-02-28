// Update text on search panel on radio button click
$('input[name=collectionsradio]').change(function () {
    switch ($('input[name=collectionsradio]:checked').val()) {
        case 'new':
            $('#existingList').hide();
            $('#newName').show();

            break;
        case 'existing':
            $('#existingList').show();
            $('#newName').hide();

            break;
    }
});