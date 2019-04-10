$(document).ready(function(){
    $('#email').change(function(){
       var email = $('#email').val();
       if(email.length > 5) {
        $('#available').html('<span class="checking">Check availability ...</span>');
        $.ajax({
            type: "get",
            url: 'check.php',
            data: 'email='+ email,
            success: function(response) {
                if(response == 'YES') {
                    $('#available').html('<span class="avai">Email is available</span>');
                } else if (response == "NO") {
                    $('#available').html('<span class="not-avai">Email is NOT available</span>');
                }

            }
        });
       } else {
        $('#available').text('Email is too short');
       }
    });
});