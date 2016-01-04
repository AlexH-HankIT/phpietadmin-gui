define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    return {
        set_service_status: function () {
            $('.servicename').each(function () {
                mylibs.check_service_status($(this));
            });
        },
        add_event_handler_servicestart: function () {
            var _this = this;

            $('.workspace').once('click', '.servicestart', function () {
                $.ajax({
                    url: require.toUrl('../service/change_service_state'),
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: {
                        'servicename': $(this).closest('tr').find('.servicename').text(),
                        'start': ''
                    },
                    type: 'post',
                    success: function () {
                        _this.set_service_status();
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
            var _this = this;

            $('.workspace').once('click', '.servicestop', function () {
                var data = {
                    'servicename': $(this).closest('tr').find('.servicename').text(),
                    'stop': ''
                };

                $.ajax({
                    url: require.toUrl('../service/change_service_state'),
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: data,
                    type: 'post',
                    success: function () {
                        _this.set_service_status();
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
            var _this = this;

            $('.workspace').once('click', '.servicerestart', function () {
                var data = {
                    'servicename': $(this).closest('tr').find('.servicename').text(),
                    'restart': ''
                };

                $.ajax({
                    url: require.toUrl('../service/change_service_state'),
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: data,
                    type: 'post',
                    success: function (data) {
                        _this.set_service_status();
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