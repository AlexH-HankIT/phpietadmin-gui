define(['jquery', 'mylibs'], function ($, mylibs) {
    var methods;

    return methods = {
        rename: function () {
            $('#rename_volume_button').once('click', function () {
                var input = $('#name_input');

                if (input.val() !== '') {
                    var data = $('#logical_volume_selector').find('option:selected').data();
                    var url = '/phpietadmin/lvm/configure';

                    $.ajax({
                        url: url + '/rename',
                        data: {
                            'vg': data.vg,
                            'lv': data.lv,
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
                } else {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'Please choose a name!'
                    });
                }
            });
        },
        focusInput: function() {
            $('#name_input', '#configure_lvm_body').focus();
        }
    };
});