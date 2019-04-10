$(document).ready(function() {
    $('.remove').click(function() {
        var container = $(this).parent();
        var cid = $(this).attr('id');
        var string = "cmt_id="+ cid;
    $.ajax({
        type: "POST",
        url: 'includes/delete_comment.php',
        cache: false,
        data: string,
        success: function() {
            container.slideUp('slow', function() {container.remove();});
        }
    });
    return false;
    });
});