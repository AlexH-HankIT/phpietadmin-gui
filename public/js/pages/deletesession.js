define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_qtip_sessiondeletebutton: function() {
            $(document).ready(function(){
                $('.sessiondeletebutton').qtip({
                    content: {
                        text: 'Normally the initiator immediately reconnects. ' +
                        'To disconnect an initiator permanently you have to deleted the acl allowing the connection ' +
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
                $(document).off('click', '.sessiondeletebutton');
                $(document).on('click', '.sessiondeletebutton', function() {
                    var data = {
                        iqn: $('#targetselection').find("option:selected").val(),
                        cid: $(this).closest('tr').find('.cid').text(),
                        sid: $(this).closest('tr').find('.sid').text()
                    };

                    var request = mylibs.doajax('/phpietadmin/targets/deletesession', data);

                    request.done(function () {
                        if (request.readyState == 4 && request.status == 200) {
                            if (request.responseText == "Success") {
                                swal({
                                    title: 'Success',
                                    type: 'success'
                                });
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: request.responseText
                                });
                            }

                            var url= 'targets/deletesession';
                            var page = url.replace('/', '_');
                            url = '/phpietadmin/' + url;

                            var array = {
                                iqn: $('#targetselection').find("option:selected").val()
                            };

                            request = mylibs.doajax(url, array);

                            request.done(function () {
                                if (request.readyState == 4 && request.status == 200) {
                                    var configuretargetbody = $('#configuretargetbody');
                                    configuretargetbody.html('');
                                    configuretargetbody.html(request.responseText);
                                    configuretargetbody.removeClass();
                                    configuretargetbody.addClass(page);
                                }
                            });

                        }
                    });
                });
            });
        }
    };
});