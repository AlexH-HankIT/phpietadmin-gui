define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        remove: function () {
            $('#delete_volume_button').once('click', function () {
                if ($('#safety_checkbox').prop("checked")) {
                    var url = '/phpietadmin/lvm/configure';
                    var $selected_volume = $('#logical_volume_selector').find("option:selected");

                    $.ajax({
                        url: url + '/delete',
                        data: {
                            'lv': $selected_volume.attr('data-lv'),
                            'vg': $selected_volume.attr('data-vg'),
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
                        text: 'Please read, understand and check the checkbox!'
                    });
                }
            });
        }
    };
});