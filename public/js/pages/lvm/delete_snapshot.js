define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        delete_snapshot: function () {
            mylibs.select_all_checkbox($('#master_checkbox'));

            $('.delete_snapshot.btn').once('click', function () {
                var url = require.toUrl('../lvm/configure/snapshot/delete'),
                    $selected = $('#logical_volume_selector').find("option:selected"),
                    $button = $(this);
                $('.delete_snapshot.checkbox:checked').each(function () {
                    $button.button('loading');
                    $.ajax({
                        url: url,
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            "snapshot": $(this).closest('tr').find('.delete_snapshot.lv_name').text(),
                            'vg': $selected.data('subtext')
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
                                    mylibs.load_lvm_target_body(url)
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
                });
            });
        }
    }
});