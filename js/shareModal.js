$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('post_id_share')){
        $('#photoModal-share').modal('show');
    }
});