define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function ($, mylibs, swal, qtip) {
    return {
        add_event_handler_addservice: function () {
            $('.workspace').once('click', '#addservice', function () {
                $('#addservicetablebody').prepend(
                    '<tr class="newrow">' +
                    '<td class="col-md-2"><input class="serviceenabled" type="checkbox" checked> <span class="label bestaetigung label-success serviceenabledspan">Success</span></td>' +
                    '<td class="col-md-8"><input class="serviceinput" type="text"> <span class="label bestaetigung label-success serviceinputspan">Success</span></td>' +
                    '<td><input class="serviceinputoldvalue" type="text" hidden></td>' +
                    '<td class="col-md-1"><a class="editservice" href="#"><span class="glyphicon glyphicon-save glyphicon-20"></td>' +
                    '<td class="col-md-1"><a class="deleteservice" href="#"><span class="glyphicon glyphicon-trash glyphicon-20"></span></a></td>' +
                    '</tr>'
                );

                $('#addservice').hide();
            });
        },
        add_event_handler_deleteservicebutton: function () {
            $('.workspace').once('click', '.deleteservice', function (e) {
                var $this_row = $(this).closest('tr');
                if ($this_row.hasClass('newrow')) {
                    $this_row.remove();
                    $('#addservice').show();
                } else {
                    swal({
                            title: "Are you sure?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it!",
                            closeOnConfirm: true
                        },
                        function () {
                            $.ajax({
                                url: require.toUrl('../service/add'),
                                beforeSend: mylibs.checkAjaxRunning(),
                                data: {
                                    'servicename': $this_row.find('.serviceinput').val(),
                                    'action': 'delete'
                                },
                                dataType: 'json',
                                type: 'post',
                                success: function (data) {
                                    if (data['code'] === 0) {
                                        $this_row.remove();
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
                }
                e.preventDefault();
            });
        },
        add_event_handler_editservicebutton: function () {
            $('.workspace').once('click', '.editservice', function (e) {
                var $this_row = $(this).closest('tr');
                var $service_input = $this_row.find('.serviceinput');
                var $edit_service_span = $('span', $this_row.closest('tr').find('.editservice'));

                // If the element has the save class, the button is pressed a second time to save the data
                if ($edit_service_span.hasClass('glyphicon-save')) {
                    var old_value = $this_row.find('.serviceinputoldvalue').val();
                    var new_value = $service_input.val();
                    var $service_input_span = $this_row.find('.serviceinputspan');

                    if (old_value === new_value) {
                        if ($service_input_span.hasClass('label-success')) {
                            $service_input_span.removeClass('label-success');
                        } else if ($service_input_span.hasClass('label-danger')) {
                            $service_input_span.removeClass('label-danger');
                        }
                        $service_input_span.addClass('label-warning').show(500).text('No changes').delay(2000).hide(0);
                        $service_input.prop('disabled', true);
                        $edit_service_span.removeClass('glyphicon-save').addClass('glyphicon-pencil');
                    } else {
                        // empty oldvalues means we add a new service here
                        var data;
                        if (old_value === '') {
                            data = {
                                'action': 'add',
                                'servicename': new_value
                            };

                        } else {
                            data = {
                                'servicename': old_value,
                                'action': 'edit',
                                'newvalue': new_value
                            };
                        }

                        $.ajax({
                            url: require.toUrl('../service/add'),
                            beforeSend: mylibs.checkAjaxRunning(),
                            data: data,
                            dataType: 'json',
                            type: 'post',
                            success: function (data) {
                                if (data['code'] === 0) {
                                    $service_input_span.text('Success');
                                    if ($service_input_span.hasClass('label-warning')) {
                                        $service_input_span.removeClass('label-warning');
                                    } else if ($service_input_span.hasClass('label-danger')) {
                                        $service_input_span.removeClass('label-danger');
                                    }
                                    $service_input_span.addClass('label-success').show(500).delay(2000).hide(0);
                                    $service_input.prop('disabled', true);
                                    $edit_service_span.removeClass('glyphicon-save').addClass('glyphicon-pencil');
                                    $this_row.find('.serviceinputoldvalue').val($service_input.val());
                                    $('#addservice').show();
                                } else {
                                    $service_input_span.text('Failed').removeClass('label-success').addClass('label-danger').show(500).delay(2000).hide(0);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: 'Something went wrong while submitting!'
                                });
                            }
                        });
                    }
                } else {
                    $service_input.prop('disabled', false);
                    $edit_service_span.removeClass('glyphicon-pencil').addClass('glyphicon-save');
                }
                e.preventDefault();
            });
        },
        add_event_handler_serviceenabled: function () {
            $('.workspace').once('change', '.serviceenabled', function (e) {
                var data;
                var $this = $(this);
                var $this_row = $this.closest('tr');
                var serviceinput = $this_row.find('.serviceinput');
                if ($this.is(':checked')) {
                    data = {
                        'servicename': serviceinput.val(),
                        'action': 'enable'
                    };
                } else {
                    data = {
                        'servicename': serviceinput.val(),
                        'action': 'disable'
                    };
                }
                
                var serviceenabledspan = $this_row.find('.serviceenabledspan');
                $.ajax({
                    url: require.toUrl('../service/add'),
                    beforeSend: mylibs.checkAjaxRunning(),
                    data: data,
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        if (data['code'] === 0) {
                            serviceenabledspan.show(500).delay(2000).hide(0);
                        } else {
                            serviceenabledspan.text('Failed').removeClass('label-success').addClass('label-danger').show(500).delay(2000).hide(0);
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
                e.preventDefault();
            });
        }
    }
});