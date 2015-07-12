define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        set_service_status: function() {
            $('.servicename').each(function() {
                mylibs.check_service_status($(this));
            });
        },
        add_event_handler_servicestart: function() {
            $(document).ready(function(){
                $(document).once('click', '.servicestart', function() {
                    var data = {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'start': ''
                    };

                    var request = mylibs.doajax('/phpietadmin/service/index', data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            methods.set_service_status();
                        }
                    });
                });
            });
        },
        add_event_handler_servicestop: function() {
            $(document).ready(function(){
                $(document).once('click', '.servicestop', function() {
                    var data = {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'stop': ''
                    };

                    var request = mylibs.doajax('/phpietadmin/service/index', data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            methods.set_service_status();
                        }
                    });
                });
            });
        },
        add_event_handler_servicerestart: function() {
            $(document).ready(function(){
                $(document).once('click', '.servicerestart', function() {
                    var data = {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'restart': ''
                    };

                    var request = mylibs.doajax('/phpietadmin/service/index', data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            methods.set_service_status();
                        }
                    });
                });
            });
        }
    }
});