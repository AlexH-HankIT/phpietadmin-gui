define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        model: function() {
            var editPasswordModal = $('#editPasswordModal'),
                formControl = $('.form-control');

            editPasswordModal.once('shown.bs.modal', function () {
                $('#inputPassword').focus();
            });

            editPasswordModal.once('hidden.bs.modal', function () {
                console.log("hide");
            });

            // Remove error, when input field is filled
            formControl.once('click', function() {
                $(this).parent('div').removeClass('has-error');
            });

            $('#savePasswordButton').once('click', function() {
                var inputPasswordVal = $('#inputPassword').val(),
                    inputPasswordRepeatVal = $('#inputPasswordRepeat').val(),
                    formControlParentDiv = formControl.parent('div');

                if (formControl.val() === '' || inputPasswordVal !== inputPasswordRepeatVal) {
                    formControlParentDiv.addClass('has-error');
                } else {
                    formControlParentDiv.addClass('has-success');
                }

                // Only close modal on success
                if (formControlParentDiv.hasClass('has-success')) {
                    // Wait a bit to inform the user of the success
                    setTimeout(function() {
                        editPasswordModal.modal('hide');

                        // Remove success class, otherwise it is still displayed, if the user opens the modal again
                        formControlParentDiv.removeClass('has-success');

                        // Empty the password input fields
                        formControl.val('');
                    }, 400);
                }
            });
        }
    };
});