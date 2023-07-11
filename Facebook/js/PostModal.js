$(document).ready(function() {
    $('.editLink').on('click', function(event) {
        event.preventDefault();
        const taskId = $(this).data('taskid');
        const url = 'index.php?id=' + taskId;
        window.location.href = url;
    });
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has('id')) {
        const taskId = urlParams.get('id');
        $('#staticBackdrop' + taskId).modal('show');
    }
});