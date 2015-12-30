define(['jquery', 'mylibs'], function ($, mylibs) {
    return {
        delete_snapshot: function () {
            mylibs.select_all_checkbox($('#master_checkbox'));

            $('.delete_snapshot.btn').once('click', function () {
                var url = require.toUrl('../lvm/configure/snapshot/delete');
                $('.delete_snapshot.checkbox:checked').each(function () {
                    $.ajax({
                        url: url,
                        data: {
                            "snapshot": $(this).closest('tr').find('.delete_snapshot.lv_name').text(),
                            "vg": $('#logical_volume_selector').find("option:selected").attr('data-vg')
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
            });
        }
    }
});