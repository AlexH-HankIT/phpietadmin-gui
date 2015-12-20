define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        enable_filter_table_plugin: function () {
            // Enable filter table plugin
            $('.searchabletable').filterTable({minRows: 0});
        },
        add_event_handler_deleteuserbutton: function () {
            $(document).once('click', '#deleteuserbutton', function () {
                var $userDeleteCheckbox = $('.userDeleteCheckbox:checked');
                if (!$userDeleteCheckbox.val()) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please select a user'
                    });
                } else {
                    $userDeleteCheckbox.each(function () {
                        var $thisRow = $(this).closest('tr'),
                            iqn = $('#targetSelect').find('option:selected').val(),
                            bodyId =  '/deleteuser',
                            url = '/phpietadmin/targets/configure/' + iqn + bodyId;

                        $.ajax({
                            url: url,
                            data: {
                                'id': $thisRow.find('.id').text(),
                                'type': $thisRow.find('.type').text()
                            },
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] === 0) {
                                    return mylibs.loadConfigureTargetBody(bodyId, iqn);
                                } else {
                                    swal({
                                        title: 'Error',
                                        type: 'error',
                                        text: data['message']
                                    });
                                }
                            },
                            error: function () {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'Something went wrong while submitting!'
                                });
                            }
                        });
                    });
                }
            });
        }
    };
});