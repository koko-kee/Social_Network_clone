$(document).ready(function() {
  $('.show').on('click', function(event) {
    event.preventDefault();
    const post_id = $(this).data('postid');
    const userid = $(this).data('userid');
    const modalId = '#exampleModal-post-' + post_id;
    $(modalId).modal('show');
    history.replaceState(null, '', '?user_id=' + userid + '&post_id=' + post_id);
    window.location.href = window.location.href;
  });
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('post_id')) {
    const post_id = urlParams.get('post_id');
    const modalId = '#exampleModal-post-' + post_id;
    $(modalId).modal('show');
  }
});
