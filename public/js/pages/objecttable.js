define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    var methods;

    return methods = {
        add_event_handler_addobjectrowbutton: function() {
            $(document).ready(function(){
                $(document).off('click', '#addobjectrowbutton');
                $(document).on('click', '#addobjectrowbutton', function() {
                    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
                    $('#addobjectstbody').append(
                        '<tr  class="newrow">' +
                        '<td>' +
                        '<select class="typeselection">' +
                        '<option class="default">Select type...</option>' +
                        '<option value="hostv4">IPv4 Host</option>' +
                        '<option value="hostv6">IPv6 Host</option>' +
                        '<option value="networkv4">IPv4 Network</option>' +
                        '<option value="networkv6">IPv6 Network</option>' +
                        '<option value="iqn">IQN</option>' +
                        '<option value="all">ALL</option>' +
                        '<option value="regex">Regex</option>' +
                        '</select>' +
                        '<span class="label label-success bestaetigung">Success</span>' +
                        '</td>' +
                        '<td>' +
                        '<input class="objectname" type="text" name="value" placeholder="Meaningful name...">' +
                        '<span class="label label-success bestaetigung">Success</span>' +
                        '</td>' +
                        '<td>' +
                        '<input class="objectvalue" type="text" name="value" placeholder="Your value...">' +
                        '<span class="label label-success bestaetigung">Success</span>' +
                        '</td>' +
                        '<td><a href="#" class="deleteobjectrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>' +
                        '<td>' +
                        '<a href="#" class="saveobjectrow"><span class="glyphicon glyphicon-save glyphicon-20" aria-hidden="true"></span></a>' +
                        '<span class="label label-success bestaetigung">Success</span>' +
                        '</td>' +
                        '</tr>'
                    );

                    $('#addobjectrowbutton').hide();
                });
            });
        },
        add_event_handler_typeselection: function() {
            $(document).ready(function(){
                // If type is "all" change input fields to "all"
                $(document).off('click', '.typeselection');
                $(document).on('change', '.typeselection', function() {
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
        add_event_handler_saveobjectrow: function() {
            $(document).ready(function(){
                $(document).off('click', '.saveobjectrow');
                $(document).on('click', '.saveobjectrow', function(event) {
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
                        var check = {
                            value: value,
                            check: "duplicated"
                        };

                        request = mylibs.doajax("/phpietadmin/objects/checkvalueexists", check);

                        request.done(function () {
                            if (request.readyState == 4 && request.status == 200) {
                                if (request.responseText == "false") {
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
                                            var data = {
                                                "type": type,
                                                "name": name,
                                                "value": value
                                            };

                                            request = mylibs.doajax("/phpietadmin/objects/add", data);

                                            request.done(function () {
                                                if (request.readyState == 4 && request.status == 200) {
                                                    // replace input fields with values
                                                    thisrow.find(".objectvalue").replaceWith(thisrow.find(".objectvalue").val());
                                                    thisrow.find(".objectname").replaceWith(thisrow.find(".objectname").val());
                                                    thisrow.find(".typeselection").replaceWith(thisrow.find(".typeselection option:selected").val());

                                                    // hide savebutton and show add button
                                                    thisrow.find('.saveobjectrow').hide();
                                                    $('#addobjectrowbutton').show();

                                                    // delete newrow class
                                                    thisrow.removeClass('newrow');
                                                }
                                            });
                                        }
                                    }
                                } else {
                                    var thisrowobjectvalue = thisrow.find(".objectvalue");
                                    thisrowobjectvalue.addClass("focusedInputerror");
                                    thisrowobjectvalue.next('.bestaetigung').addClass("label-danger");
                                    thisrowobjectvalue.next('.bestaetigung').text("Already exists");
                                    thisrowobjectvalue.next('.bestaetigung').show(500);
                                    thisrowobjectvalue.next('.bestaetigung').delay(2000).hide(0);
                                }
                            }
                        });
                    }
                });
            });
        },
        add_event_handler_objectvalue: function() {
            $(document).ready(function(){
                $(document).off('focus', '.objectvalue');
                $(document).on('focus', '.objectvalue', function() {
                    var objectvalue = $(".objectvalue");
                    if (objectvalue.hasClass("focusedInputerror")) {
                        objectvalue.removeClass("focusedInputerror");
                    }
                });
            });
        },
        add_event_handler_objectname: function() {
            $(document).ready(function(){
                $(document).off('focus', '.objectname');
                $(document).on('focus', '.objectname', function() {
                    var objectname = $(".objectname");
                    if (objectname.hasClass("focusedInputerror")) {
                        objectname.removeClass("focusedInputerror");
                    }
                });
            });
        },
        add_event_handler_deleteobjectrow: function() {
            $(document).ready(function(){
                $(document).off('click', '.deleteobjectrow');
                $(document).on('click', '.deleteobjectrow', function(event) {
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
                            function(){
                                var id = sel.find('.id').text();

                                var data = {
                                    "id": id
                                };

                                var request = mylibs.doajax("/phpietadmin/objects/delete", data);

                                request.done(function () {
                                    if (request.readyState == 4 && request.status == 200) {
                                        if (request.responseText == "Success") {
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
                                                    text: request.responseText
                                                });
                                        }
                                    } else {
                                        swal({
                                            title: 'Error',
                                            type: 'error',
                                            text: request.responseText
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