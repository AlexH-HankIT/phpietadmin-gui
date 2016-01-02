define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        rename: function () {
            $('#rename_volume_button').once('click', function () {
                var input = $('#name_input'),
                    $button = $(this);

                if (input.length === 0) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please choose a name!'
                    });
                } else {
                    var $selected = $('#logical_volume_selector').find("option:selected"),
                        url = require.toUrl('../lvm/configure');

                    $button.button('loading');
                    $.ajax({
                        url: url + '/rename',
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            'vg': $selected.data('subtext'),
                            'lv': $selected.text(),
                            'name': input.val()
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
                                }, function () {
                                    $button.button('reset');
                                });
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            }, function () {
                                $button.button('reset');
                            });
                        }
                    });
                }
            });
        },
        focusInput: function () {
            $('#name_input', '#configure_lvm_body').focus();
        }
    };
});