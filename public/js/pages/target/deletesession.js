define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_qtip_sessiondeletebutton: function() {
            $(document).ready(function(){
                $('.sessiondeletebutton').qtip({
                    content: {
                        text: 'Normally the initiator immediately reconnects. ' +
                        'To disconnect an initiator permanently you have to delete the acl allowing the connection ' +
                        'before deleting the session.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
            });
        },
        add_event_handler_sessiondeletebutton: function() {
            $(document).ready(function(){
                $(document).once('click', '.sessiondeletebutton', function() {
                    var iqn = $('#targetselection').find("option:selected").val();

                    $.ajax({
                        url: '/phpietadmin/targets/configure/deletesession',
                        data: {
                            iqn: iqn,
                            sid: $(this).closest('tr').find('.sid').text()
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] == 0) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: data['message']
                                });

                                $.ajax({
                                    url: '/phpietadmin/targets/configure/deletesession',
                                    data: {iqn: iqn},
                                    dataType: 'html',
                                    type: 'post',
                                    success: function (data) {
                                        var url= 'targets/deletesession';
                                        var page = url.replace('/', '_');
                                        url = '/phpietadmin/' + url;

                                        var configuretargetbody = $('#configuretargetbody');
                                        configuretargetbody.html('');
                                        configuretargetbody.html(data);
                                        configuretargetbody.removeClass();
                                        configuretargetbody.addClass(page);
                                    },
                                    error: function() {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: 'Something went wrong while reloading!'
                                        });
                                    }
                                });
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: data['message']
                                });
                            }
                        },
                        error: function() {
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
    };
});