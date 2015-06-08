define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $(document).on('click', '#addobjectrowbutton', function() {
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

        // If type is "all" change input fields to "all"
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

        $(document).on('click', '.saveobjectrow', function() {
            var thisrow = $(this).closest("tr");

            type = thisrow.find(".typeselection option:selected").val();
            name = thisrow.find(".objectname").val();
            value = thisrow.find(".objectvalue").val();

            if (type == "Select type...") {
                thisrow.find('.typeselection').next('.bestaetigung').addClass("label-danger");
                thisrow.find(".typeselection").next('.bestaetigung').text("Required");
                thisrow.find(".typeselection").next('.bestaetigung').show(500);
                thisrow.find(".typeselection").next('.bestaetigung').delay(2000).hide(0);
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
                                            location.reload();
                                        }
                                    });
                                }
                            }
                        } else {
                            thisrow.find(".objectvalue").addClass("focusedInputerror");
                            thisrow.find(".objectvalue").next('.bestaetigung').addClass("label-danger");
                            thisrow.find(".objectvalue").next('.bestaetigung').text("Already exists");
                            thisrow.find(".objectvalue").next('.bestaetigung').show(500);
                            thisrow.find(".objectvalue").next('.bestaetigung').delay(2000).hide(0);
                        }
                    }
                });
            }
        });



        $(document).on('focus', '.objectvalue', function() {
            objectvalue = $(".objectvalue");
            if (objectvalue.hasClass("focusedInputerror")) {
                objectvalue.removeClass("focusedInputerror");
            }
        });

        $(document).on('focus', '.objectname', function() {
            objectname = $(".objectname");
            if (objectname.hasClass("focusedInputerror")) {
                objectname.removeClass("focusedInputerror");
            }
        });

        $(document).on('click', '.editobjectrow', function() {
            var thisrow = $(this).closest("tr");

            thisrow.find(".objectname").removeAttr("disabled");
            thisrow.find(".objectvalue").removeAttr("disabled");

            // Replace edit button with save button
            // on save button click
            // check if name and value are different and ajax them to the server
            // objects/edit will make the changes
            // refresh page
            // done
        });

        $(document).on('click', '.deleteobjectrow', function() {
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

                        request = mylibs.doajax("/phpietadmin/objects/delete", data);

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
                                        },
                                        function () {
                                            sel.remove();
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
});