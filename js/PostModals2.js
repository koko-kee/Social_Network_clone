$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('edit_id')) {
        $('#photoModal-2').modal('show');
    }
});