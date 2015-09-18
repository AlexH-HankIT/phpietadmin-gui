define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        set_service_status: function() {
            $(function() {
                $('.servicename').each(function() {
                    mylibs.check_service_status($(this));
                });
            });
        },
        add_event_handler_servicestart: function() {
            $(function() {
                $(document).once('click', '.servicestart', function() {
                    var data = {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'start': ''
                    };

                    $.ajax({
                        url: '/phpietadmin/service/change_service_state',
                        data: data,
                        type: 'post',
                        success: function(data) {
                            methods.set_service_status();
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
        },
        add_event_handler_servicestop: function() {
            $(function() {
                $(document).once('click', '.servicestop', function() {
                    var data = {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'stop': ''
                    };

                    $.ajax({
                        url: '/phpietadmin/service/change_service_state',
                        data: data,
                        type: 'post',
                        success: function(data) {
                            methods.set_service_status();
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
        },
        add_event_handler_servicerestart: function() {
            $(function() {
                $(document).once('click', '.servicerestart', function() {
                    var data = {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'restart': ''
                    };

                    $.ajax({
                        url: '/phpietadmin/service/change_service_state',
                        data: data,
                        type: 'post',
                        success: function(data) {
                            methods.set_service_status();
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
    }
});