define(['jquery', 'mylibs', 'sweetalert', 'qtip'], function($, mylibs, swal, qtip) {
    var methods;

    return methods = {
        add_event_handler_addservice: function() {
            $(document).ready(function(){
                $(document).once('click', '#addservice', function() {
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    $('#addservicetablebody').append(
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
            $(document).ready(function(){
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
                                var data = {
                                    "servicename": row.find('.serviceinput').val(),
                                    "action": 'delete'
                                };

                                var request = mylibs.doajax('/phpietadmin/service/add', data);

                                request.done(function() {
                                    if (request.readyState == 4 && request.status == 200) {
                                        if (request.responseText != 0) {
                                            swal({
                                                title: 'Error',
                                                type: 'error',
                                                text: request.responseText
                                            });
                                        } else {
                                            swal({
                                                    title: 'Success',
                                                    type: 'success'
                                                },
                                                function () {
                                                    row.remove();
                                                });
                                        }
                                    }
                                });
                            });
                    }
                    e.preventDefault();
                });
            });
        },
        add_event_handler_editservicebutton: function() {
            $(document).ready(function(){
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

                            // check if new or changed services is already used
                            var request = mylibs.doajax('/phpietadmin/service/check_service_already_exists', data);

                            request.done(function() {
                                if (request.readyState == 4 && request.status == 200) {
                                    console.log(request.responseText);
                                    if (request.responseText == 'false') {
                                        request = mylibs.doajax('/phpietadmin/service/add', data);

                                        request.done(function() {
                                            if (request.readyState == 4 && request.status == 200) {
                                                if (request.responseText != 0) {
                                                    serviceinputspan.text('Failed');
                                                    serviceinputspan.removeClass('label-success');
                                                    serviceinputspan.addClass('label-danger');
                                                    serviceinputspan.show(500);
                                                    serviceinputspan.delay(2000).hide(0);
                                                } else {
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
                                                }
                                            }
                                        });
                                    } else {
                                        serviceinputspan.text('Already in use');
                                        serviceinputspan.removeClass('label-success');
                                        serviceinputspan.addClass('label-danger');
                                        serviceinputspan.show(500);
                                        serviceinputspan.delay(2000).hide(0);
                                    }
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
            $(document).ready(function(){
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

                    var request = mylibs.doajax('/phpietadmin/service/add', data);

                    request.done(function() {
                        if (request.readyState == 4 && request.status == 200) {
                            var serviceenabledspan = row.find('.serviceenabledspan');
                            if (request.responseText != 0) {
                                serviceenabledspan.text('Failed');
                                serviceenabledspan.removeClass('label-success');
                                serviceenabledspan.addClass('label-danger');
                                serviceenabledspan.show(500);
                                serviceenabledspan.delay(2000).hide(0);
                            } else {
                                serviceenabledspan.show(500);
                                serviceenabledspan.delay(2000).hide(0);
                            }
                        }
                    });
                    e.preventDefault();
                });
            });
        }
    }
});