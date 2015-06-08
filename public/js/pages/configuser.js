define(['jquery', 'mylibs', 'sweetalert', 'sha256'], function($, mylibs, swal, sha256) {
    $(function() {
        password1 = $('.password1');
        password2 = $('.password2');
        editpassword = $('#editpassword');
        savepasswordatag = $('#savepassworda');

        $(document).on('click', '#editpassword', function() {
            if (password1.prop('disabled') == true && password2.prop('disabled') == true) {
                password1.prop('disabled', false);
                password2.prop('disabled', false);
                password1.val('');
                password2.val('');
                password1.focus();
                editpassword.removeClass('glyphicon-pencil');
                editpassword.addClass('glyphicon-remove');
                savepasswordatag.show();
            } else {
                password1.prop('disabled', true);
                password2.prop('disabled', true);
                password1.val('           ');
                password2.val('           ');

                editpassword.removeClass('glyphicon-remove');
                editpassword.addClass('glyphicon-pencil');
                savepasswordatag.hide();
            }
        });

        $(document).on('click', '#savepassword', function() {
            // Check if password1 and password2 matches
            // Create sha256 hash from password console.log(sha256.hash("abc"));
            // Ajax password to server
            // Display success or error message
        });
    });
});