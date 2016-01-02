define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        remove: function () {
            $('#delete_volume_button').once('click', function () {
                if ($('#safety_checkbox').prop("checked")) {
                    var url = require.toUrl('../lvm/configure'),
                        $selected = $('#logical_volume_selector').find("option:selected"),
                        $button = $(this);

                    $button.button('loading');
                    $.ajax({
                        url: url + '/delete',
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            'vg': $selected.data('subtext'),
                            'lv': $selected.text(),
                            'delete': true
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: data['message']
                                }, function () {
                                    mylibs.load_workspace(url)
                                });
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: data['message']
                                }, function() {
                                    $button.button('reset');
                                });
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            }, function() {
                                $button.button('reset');
                            });
                        }
                    });
                } else {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please read, understand and check the checkbox!'
                    });
                }
            });
        }
    };
});