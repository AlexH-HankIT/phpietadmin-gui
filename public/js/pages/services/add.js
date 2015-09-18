define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_event_handler_addservice: function() {
            $(function() {
                $(document).once('click', '#addservice', function() {
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
            });
        },
        add_event_handler_deleteservicebutton: function() {
            $(function() {
                $(document).once('click', '.deleteservice', function(e) {
                    var row = $(this).closest('tr');
                    if (row.hasClass('newrow')) {
                        row.remove();
                        $('#addservice').show();
                    } else {
                        swal({
                                title: "Are you sure?",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, delete it!",
                                closeOnConfirm: false
                            },
                            function(){
                                $.ajax({
                                    url: '/phpietadmin/service/add',
                                    data: {
                                        "servicename": row.find('.serviceinput').val(),
                                        "action": 'delete'
                                    },
                                    dataType: 'json',
                                    type: 'post',
                                    success: function(data) {
                                        if (data['code'] == 0) {
                                            swal({
                                                title: 'Success',
                                                type: 'success',
                                                text: data['message']
                                            },
                                                function () {
                                                    row.remove();
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
                    }
                    e.preventDefault();
                });
            });
        },
        add_event_handler_editservicebutton: function() {
            $(function() {
                $(document).once('click', '.editservice', function(e) {
                    var row = $(this).closest('tr');
                    var serviceinput = row.find('.serviceinput');
                    var editservice = $(this).closest('tr').find('.editservice');
                    var editservicespan = $('span', editservice);

                    // If the element has the save class, the button is pressed a second time to save the data
                    if (editservicespan.hasClass('glyphicon-save')) {
                        var oldvalue = row.find('.serviceinputoldvalue').val();
                        var newvalue = serviceinput.val();
                        var serviceinputspan = row.find('.serviceinputspan');

                        if (oldvalue == newvalue) {
                            serviceinputspan.text('No changes');

                            if (serviceinputspan.hasClass('label-success')) {
                                serviceinputspan.removeClass('label-success');
                            } else if (serviceinputspan.hasClass('label-danger')) {
                                serviceinputspan.removeClass('label-danger');
                            }

                            serviceinputspan.addClass('label-warning');
                            serviceinputspan.show(500);
                            serviceinputspan.delay(2000).hide(0);
                            serviceinput.prop('disabled', true);
                            editservicespan.removeClass('glyphicon-save');
                            editservicespan.addClass('glyphicon-pencil');
                        } else {
                            // empty oldvalues means we add a new service here
                            var data;
                            if (oldvalue == '') {
                                data = {
                                    "action": 'add',
                                    "servicename": newvalue
                                };

                            } else {
                                data = {
                                    "servicename": oldvalue,
                                    "action": 'edit',
                                    "newvalue": newvalue
                                };


                            }

                            $.ajax({
                                url: '/phpietadmin/service/add',
                                data: data,
                                dataType: 'json',
                                type: 'post',
                                success: function(data) {
                                    if (data['code'] == 0) {
                                        serviceinputspan.text('Success');
                                        if (serviceinputspan.hasClass('label-warning')) {
                                            serviceinputspan.removeClass('label-warning');
                                        } else if (serviceinputspan.hasClass('label-danger')) {
                                            serviceinputspan.removeClass('label-danger');
                                        }
                                        serviceinputspan.addClass('label-success');
                                        serviceinputspan.show(500);
                                        serviceinputspan.delay(2000).hide(0);
                                        serviceinput.prop('disabled', true);
                                        editservicespan.removeClass('glyphicon-save');
                                        editservicespan.addClass('glyphicon-pencil');
                                        row.find('.serviceinputoldvalue').val(serviceinput.val());
                                        $('#addservice').show();
                                    } else {
                                        serviceinputspan.text('Failed');
                                        serviceinputspan.removeClass('label-success');
                                        serviceinputspan.addClass('label-danger');
                                        serviceinputspan.show(500);
                                        serviceinputspan.delay(2000).hide(0);
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
                        }
                    } else {
                        serviceinput.prop('disabled', false);
                        editservicespan.removeClass('glyphicon-pencil');
                        editservicespan.addClass('glyphicon-save');
                    }
                    e.preventDefault();
                });
            });
        },
        add_event_handler_serviceenabled: function() {
            $(function() {
                $(document).once('change', '.serviceenabled', function(e) {
                    var data;
                    var row = $(this).closest('tr');
                    var serviceinput = row.find('.serviceinput');
                    var clicked =  $(this);
                    if($(this).is(":checked")) {
                        data = {
                            "servicename": serviceinput.val(),
                            "action": 'enable'
                        };
                    } else {
                        data = {
                            "servicename": serviceinput.val(),
                            "action": 'disable'
                        };
                    }


                    var serviceenabledspan = row.find('.serviceenabledspan');
                    $.ajax({
                        url: '/phpietadmin/service/add',
                        data: data,
                        dataType: 'json',
                        type: 'post',
                        success: function(data) {
                            if (data['code'] == 0) {
                                serviceenabledspan.show(500);
                                serviceenabledspan.delay(2000).hide(0);
                            } else {
                                serviceenabledspan.text('Failed');
                                serviceenabledspan.removeClass('label-success');
                                serviceenabledspan.addClass('label-danger');
                                serviceenabledspan.show(500);
                                serviceenabledspan.delay(2000).hide(0);
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
                    e.preventDefault();
                });
            });
        }
    }
});