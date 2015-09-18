define(['jquery', 'mylibs', 'sweetalert'], function ($, mylibs, swal) {
    var methods;

    return methods = {
        enable_filter_table_plugin: function () {
            $(function() {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows: 0});
            });
        },
        add_event_handler_addobjectrowbutton: function () {
            $(function() {
                $(document).once('click', '#addobjectrowbutton', function () {
                    $('#template').clone().prependTo('#addobjectstbody').removeAttr('id hidden').addClass("newrow");
                    $('#addobjectrowbutton').hide();
                });
            });
        },
        add_event_handler_typeselection: function () {
            $(function() {
                // If type is "all" change input fields to "all"
                $(document).once('change', '.typeselection', function () {
                    var thisrow = $(this).closest("tr");
                    var type = thisrow.find(".typeselection option:selected").val();
                    var objectname = thisrow.find(".objectname");
                    var objectvalue = thisrow.find(".objectvalue");

                    if (type == "all") {
                        objectname.prop('disabled', true);
                        objectvalue.prop('disabled', true);
                        objectname.val("ALL");
                        objectvalue.val("ALL");
                    } else {
                        if (objectname.val() == "ALL") {
                            objectname.prop('disabled', false);
                            objectname.val("");
                        }
                        if (objectvalue.val() == "ALL") {
                            objectvalue.prop('disabled', false);
                            objectvalue.val("");
                        }
                    }
                });
            });
        },
        add_event_handler_saveobjectrow: function () {
            $(function() {
                $(document).once('click', '.saveobjectrow', function (event) {
                    event.preventDefault();
                    var thisrow = $(this).closest("tr");

                    var type = thisrow.find(".typeselection option:selected").val();
                    var name = thisrow.find(".objectname").val();
                    var value = thisrow.find(".objectvalue").val();
                    var typeselection_bestaetigung = thisrow.find('.typeselection').next('.bestaetigung');

                    if (type == "Select type...") {
                        typeselection_bestaetigung.addClass("label-danger");
                        typeselection_bestaetigung.text("Required");
                        typeselection_bestaetigung.show(500);
                        typeselection_bestaetigung.delay(2000).hide(0);
                    } else if (name == "") {
                        thisrow.find(".objectname").addClass("focusedInputerror");
                        thisrow.find(".objectname").next('.bestaetigung').addClass("label-danger");
                        thisrow.find(".objectname").next('.bestaetigung').text("Required");
                        thisrow.find(".objectname").next('.bestaetigung').show(500);
                        thisrow.find(".objectname").next('.bestaetigung').delay(2000).hide(0);
                    } else if (value == "") {
                        thisrow.find(".objectvalue").addClass("focusedInputerror");
                        thisrow.find(".objectvalue").next('.bestaetigung').addClass("label-danger");
                        thisrow.find(".objectvalue").next('.bestaetigung').text("Required");
                        thisrow.find(".objectvalue").next('.bestaetigung').show(500);
                        thisrow.find(".objectvalue").next('.bestaetigung').delay(2000).hide(0);
                    } else {
                        if (type == "hostv4" && !mylibs.validateipv4(value)) {
                            thisrow.find(".objectvalue").addClass("focusedInputerror");
                            thisrow.find(".objectvalue").next('.bestaetigung').addClass("label-danger");
                            thisrow.find(".objectvalue").next('.bestaetigung').text("Invalid IPv4");
                            thisrow.find(".objectvalue").next('.bestaetigung').show(500);
                            thisrow.find(".objectvalue").next('.bestaetigung').delay(2000).hide(0);
                        } else {
                            if (type == "networkv4" && !mylibs.validateipv4network(value)) {
                                thisrow.find(".objectvalue").addClass("focusedInputerror");
                                thisrow.find(".objectvalue").next('.bestaetigung').addClass("label-danger");
                                thisrow.find(".objectvalue").next('.bestaetigung').text("Invalid IPv4 Network");
                                thisrow.find(".objectvalue").next('.bestaetigung').show(500);
                                thisrow.find(".objectvalue").next('.bestaetigung').delay(2000).hide(0);
                            } else {
                                var thisrowobjectvalue = thisrow.find(".objectvalue");
                                $.ajax({
                                    url: '/phpietadmin/objects/add',
                                    data: {
                                        "type": type,
                                        "name": name,
                                        "value": value
                                    },
                                    dataType: 'json',
                                    type: 'post',
                                    success: function (data) {
                                        if (data['code'] == 0) {
                                            // replace input fields with values
                                            thisrow.find(".objectvalue").replaceWith(thisrow.find(".objectvalue").val());
                                            thisrow.find(".objectname").replaceWith(thisrow.find(".objectname").val());
                                            thisrow.find(".typeselection").replaceWith(thisrow.find(".typeselection option:selected").val());

                                            // hide savebutton and show add button
                                            thisrow.find('.saveobjectrow').hide();
                                            $('#addobjectrowbutton').show();

                                            // delete new row class
                                            thisrow.removeClass('newrow');
                                        } else if (data['code'] == 4 || data['code'] == 6) {
                                            if (data['field'] == 'value') {
                                                thisrowobjectvalue.addClass("focusedInputerror");
                                                thisrowobjectvalue.next('.bestaetigung').addClass("label-danger");
                                                thisrowobjectvalue.next('.bestaetigung').text('Value already exists!');
                                                thisrowobjectvalue.next('.bestaetigung').show(500);
                                                thisrowobjectvalue.next('.bestaetigung').delay(2000).hide(0);
                                            } else {
                                                var thisrowobjectname = thisrow.find(".objectname");
                                                thisrowobjectname.addClass("focusedInputerror");
                                                thisrowobjectname.next('.bestaetigung').addClass("label-danger");
                                                thisrowobjectname.next('.bestaetigung').text('Name already exists!');
                                                thisrowobjectname.next('.bestaetigung').show(500);
                                                thisrowobjectname.next('.bestaetigung').delay(2000).hide(0);
                                            }
                                        } else {
                                            thisrowobjectvalue.addClass("focusedInputerror");
                                            thisrowobjectvalue.next('.bestaetigung').addClass("label-danger");
                                            thisrowobjectvalue.next('.bestaetigung').text("Unknown error");
                                            thisrowobjectvalue.next('.bestaetigung').show(500);
                                            thisrowobjectvalue.next('.bestaetigung').delay(2000).hide(0);
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
                            }
                        }
                    }
                });
            });
        },
        add_event_handler_objectvalue: function () {
            $(function() {
                $(document).once('focus', '.objectvalue', function () {
                    var objectvalue = $(".objectvalue");
                    if (objectvalue.hasClass("focusedInputerror")) {
                        objectvalue.removeClass("focusedInputerror");
                    }
                });
            });
        },
        add_event_handler_objectname: function () {
            $(function() {
                $(document).once('focus', '.objectname', function () {
                    var objectname = $(".objectname");
                    if (objectname.hasClass("focusedInputerror")) {
                        objectname.removeClass("focusedInputerror");
                    }
                });
            });
        },
        add_event_handler_deleteobjectrow: function () {
            $(function() {
                $(document).once('click', '.deleteobjectrow', function (event) {
                    event.preventDefault();
                    var sel = $(this).closest('tr');

                    if (sel.hasClass('newrow')) {
                        sel.remove();
                        $('#addobjectrowbutton').show();
                    } else {
                        swal({
                                title: "Are you sure?",
                                text: "The object won't be deleted from the iet allow files!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, delete it!",
                                closeOnConfirm: false
                            },
                            function () {
                                $.ajax({
                                    url: '/phpietadmin/objects/delete',
                                    data: {
                                        "id": sel.find('.id').text()
                                    },
                                    dataType: 'json',
                                    type: 'post',
                                    success: function (data) {
                                        if (data['code'] == 0) {
                                            swal({
                                                    title: 'Success',
                                                    type: 'success'
                                                },
                                                function () {
                                                    sel.remove();
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
                    }
                });
            });
        }
    };
});