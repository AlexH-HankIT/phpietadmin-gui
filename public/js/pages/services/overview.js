define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    return {
        set_service_status: function () {
            $('.servicename').each(function () {
                mylibs.check_service_status($(this));
            });
        },
        add_event_handler_servicestart: function () {
            $('.workspace').once('click', '.servicestart', function () {
                $.ajax({
                    url: '/phpietadmin/service/change_service_state',
                    data: {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'start': ''
                    },
                    type: 'post',
                    success: function () {
                        methods.set_service_status();
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
        },
        add_event_handler_servicestop: function () {
            $('.workspace').once('click', '.servicestop', function () {
                var data = {
                    'servicename': $(this).closest('tr').find('.servicename').text(),
                    'stop': ''
                };

                $.ajax({
                    url: '/phpietadmin/service/change_service_state',
                    data: data,
                    type: 'post',
                    success: function () {
                        methods.set_service_status();
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
        },
        add_event_handler_servicerestart: function () {
            $('.workspace').once('click', '.servicerestart', function () {
                var data = {
                    'servicename': $(this).closest('tr').find('.servicename').text(),
                    'restart': ''
                };

                $.ajax({
                    url: '/phpietadmin/service/change_service_state',
                    data: data,
                    type: 'post',
                    success: function (data) {
                        methods.set_service_status();
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
        }
    }
});