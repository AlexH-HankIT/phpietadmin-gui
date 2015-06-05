/* Document ready open */
$(function() {
    /* Selectors start */
    var iqninput = $("#iqninput");
    var ietpermissiontargetselection = $('#ietpermissiontargetselection');
    var ietpermissiontargettable = $('#ietpermissiontargettable');
    var maplun = $('#maplunmanuelinput');
    var autoselection = $("#maplunautoselection");
    var sel = $('#logicalvolumedeleteselection');
    var deleteluniqnselection = $('#deleteluniqnselection');

    /* Selectors end */

    /* Menu */
    $('#menuhome').qtip({
        content: {
            text: 'Home'
        },
        style: {
            classes: 'qtip-youtube'
        }
    });

    $('#menulogout').qtip({
        content: {
            text: 'Logout'
        },
        style: {
            classes: 'qtip-youtube'
        }
    });


    /* Used in views/targets/addtarget.php
       Validates the iqn input field
       Save default input for later restore */
    var data = iqninput.val();

    iqninput.keydown( function(e) {
        // Prevent default data from being deleted
        var oldvalue=$(this).val();
        var field=this;
        setTimeout(function () {
            if(field.value.indexOf(data) !== 0) {
                $(field).val(oldvalue);
            }
        }, 1);

        // disable shift button
        if (e.shiftKey) {
            e.preventDefault();
        } else if (e.which == 8) {
            // keydown 8 is the deleted button
            return true;
            // prevent other special chars
        } else if (e.which < 48 || (e.which > 57 && e.which < 65) || (e.which > 90 && e.which < 97) || e.which > 122) {
            e.preventDefault();
        }
    });

    /* Focus the iqninput in views/targets/addtarget.php when the site is loaded */
    iqninput.focus();
    var $thisVal = iqninput.val();
    iqninput.val('').val($thisVal);

    /* remove error if field is clicked */
    iqninput.click(function () {
        iqninput.removeClass("focusedInputerror");
    });

    // Select active menu element
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".nav a").each(function () {
        var href = $(this).attr('href');
        if (path.substring(0, href.length) === href) {
            $(this).closest('li').addClass('active');
            $(this).closest('li').parents().addClass('active');
        }
    });


    /* Used in /phpietadmin/overview/initiators */
    ietpermissiontargetselection.on('change', function () {
        if (ietpermissiontargetselection.find('option:selected').val() == "default") {
            ietpermissiontargettable.hide();
        } else {
            if(ietpermissiontargettable.is(':hidden')) {
                ietpermissiontargettable.show();
            }
            var data = {
                "iqn": ietpermissiontargetselection.find('option:selected').val()
            };

            request = doajax("/phpietadmin/overview/initiators", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    $('#ietpermissiontargettable').html(request.responseText);
                }
            });
        }
    });

    /* shows/hides the manual input in views/targets/mapulun.php */
    maplun.hide();
    $("#check").change(function() {
        if(maplun.is(':hidden')) {
            maplun.attr("required");
            maplun.show();
            autoselection.hide();
        } else {
            autoselection.show();
            maplun.hide();
        }
    });

    /* Used in views/target/addtarget.php */
    $(document).on('click', '#addtargetbutton', function(){

        var def = $('#defaultiqn').val();

        if (iqninput.val() == "") {
            iqninput.addClass("focusedInputerror");
            return false;
        } else {
            var data = {
                "name": def + iqninput.val()
            };

            request = doajax("/phpietadmin/targets/addtarget", data);

            request.done(function() {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText == 'Success') {
                        swal({
                                title: 'Success',
                                type: 'success'
                            },
                            function () {
                                location.reload();
                            });
                    } else {
                        swal({
                                title: 'Error',
                                type: 'error',
                                text: request.responseText
                            },
                            function () {
                                location.reload();
                            });
                    }
                }
            })
        }
    });

    /* Used in views/permisssion/addrule.php */
    $(document).on('click', '#addallowrulebutton', function(){
        var selector_targetselection = $('#targetselection');

        var iqn = selector_targetselection.find("option:selected").val();
        var defaultvalue = selector_targetselection.find('#default').val();

        if (iqn == defaultvalue) {
            alert("Please select a iqn!");
        } else if (!$("input[name='objectradio']:checked").val()) {
            alert('Please select a object!');
        } else {
            var id = $("input[name='objectradio']:checked").closest('tr').find('.objectid').text();
            var type = $("input[name='type']:checked").val();

            var data = {
                "iqn": iqn,
                "type": type,
                "id": id
            };

            request = doajax("/phpietadmin/permission/addrule", data);

            request.done(function() {
                if (request.readyState == 4 && request.status == 200) {
                    alert(request.responseText);
                    location.reload();
                }
            });
        }
    });

    /* Used in views/config/objecttable.php */
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
            '<td><a href="#" class="deleteobjectrow"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
            '<td>' +
            '<a href="#" class="saveobjectrow"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a>' +
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

    /* Used in views/objects/objecttable.php */
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

            request = doajax("/phpietadmin/objects/checkvalueexists", check);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText == "false") {
                        if (type == "hostv4" && !validateipv4(value)) {
                            thisrow.find(".objectvalue").addClass("focusedInputerror");
                            thisrow.find(".objectvalue").next('.bestaetigung').addClass("label-danger");
                            thisrow.find(".objectvalue").next('.bestaetigung').text("Invalid IPv4");
                            thisrow.find(".objectvalue").next('.bestaetigung').show(500);
                            thisrow.find(".objectvalue").next('.bestaetigung').delay(2000).hide(0);
                        } else {
                            if (type == "networkv4" && !validateipv4network(value)) {
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

                                request = doajax("/phpietadmin/objects/add", data);

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

    $('.searchabletable').filterTable({minRows:0});

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
            if (confirm("Are your sure?") == true) {
                var id = sel.find('.id').text();

                var data = {
                    "id": id
                };

                request = doajax("/phpietadmin/objects/delete", data);

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == "Success") {
                            alert("Success");
                            sel.remove();
                        } else if (request.responseText == "Failed") {
                            alert("Failed");
                        } else {
                            alert("Unkown");
                        }
                    } else {
                        alert("Failed");
                    }
                });
            }
        }
    });

    /* Used in views/permissions/deleterule.php */
    $(document).on('change', '#targetselection', function() {
        var selector_targetselection = $('#targetselection');
        var iqn = selector_targetselection.find("option:selected").val();
        var ruletype = $("input[name='deleteruletype']:checked").val();
        var defaultvalue = selector_targetselection.find('#default').val();

        if (iqn !== defaultvalue) {
            var data = {
                "iqn": iqn,
                "ruletype": ruletype
            };

            request = doajax("/phpietadmin/permission/deleterule", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText == "false") {
                        alert("No rules set for this target!");
                        selector_targetselection.val('default');
                        $('#deleteruletable').html('');
                    } else {
                        $('#deleteruletable').html(request.responseText);
                    }
                }
            });
        } else {
            $('#deleteruletable').html('');
        }
    });

    $(document).on('change', 'input[name="deleteruletype"]', function(){
        var selector_targetselection = $('#targetselection');
        var iqn = selector_targetselection.find("option:selected").val();
        var ruletype = $("input[name='deleteruletype']:checked").val();
        var defaultvalue = selector_targetselection.find('#default').val();

        if (iqn !== defaultvalue) {
            var data = {
                "iqn": iqn,
                "ruletype": ruletype
            };

            request = doajax("/phpietadmin/permission/deleterule", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText == "false") {
                        alert("No rules set for this target!");
                        selector_targetselection.val('default');
                        $('#deleteruletable').html('');
                    } else {
                        $('#deleteruletable').html(request.responseText);
                    }
                }
            });
        } else {
            $('#deleteruletable').html('');
        }
    });

    $(document).on('click', '#deleterulebutton', function(){
        var selector_targetselection = $('#targetselection');
        var iqn = selector_targetselection.find("option:selected").val();
        var ruletype = $("input[name='deleteruletype']:checked").val();
        var defaultvalue = selector_targetselection.find('#default').val();

        if (iqn !== defaultvalue) {
            var deleteobjectradio = $("input[name='deleteobjectradio']:checked").closest("tr").find('.objectvalue').text()

            if (deleteobjectradio == "") {
                alert("Please select a object/orphan");
            } else {

                var data = {
                    "iqn": iqn,
                    "value": deleteobjectradio,
                    "ruletype": ruletype
                };

                request = doajax("/phpietadmin/permission/deleterule", data);

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == "Success") {
                            alert("Success");
                            location.reload();
                        } else if (request.responseText == "Failed") {
                            alert("Failed");
                        } else {
                            alert("Unkown");
                        }
                    } else {
                        alert("Failed");
                    }
                });
            }
        }
    });


    /* Used in views/lvm/delete.php */
    $(document).on('click', '#logicalvolumedeletebutton', function(){
        if(sel.find('option:selected').val() == $("#default").val()) {
            alert('Error - Please select a volume!');
        } else {
            var data = {
                "target": sel.find('option:selected').val()
            };

            request = doajax("/phpietadmin/lvm/delete", data);

            request.done(function() {
                if (request.readyState == 4 && request.status == 200) {
                    $('#logicalvolumedeletecontent').html(request.responseText);
                }
            });
        }
    });

    /* Configuration menu */
    $(document).on('click', '#config-menu a', function(){
        if( $('span', this).hasClass('glyphicon-pencil') ) {
            input = $(this).prev();
            input.removeAttr("disabled");
            $('span', this).removeClass("glyphicon-pencil");
            $('span', this).addClass("glyphicon-ok");
        } else {
            clicked = $(this);
            option = clicked.attr("href").substring(1);
            value = clicked.prev().val();
            $.ajax({url: "/phpietadmin/config/edit?option=" + option + "&value=" + value, success: function(result){
                if (result.indexOf("Success")  >= 0) {
                    clicked.next('.bestaetigung').removeClass("label-danger");
                    clicked.next('.bestaetigung').addClass("label-success");
                    clicked.next('.bestaetigung').text("Success");
                    clicked.next('.bestaetigung').show(500);
                    clicked.next('.bestaetigung').delay(1000).hide(0);
                    input = clicked.prev();
                    input.prop('disabled', true);
                    $('span', clicked).removeClass("glyphicon-ok");
                    $('span', clicked).addClass("glyphicon-pencil");
                } else {
                    clicked.next('.bestaetigung').removeClass("label-success");
                    clicked.next('.bestaetigung').addClass("label-danger");
                    clicked.next('.bestaetigung').text("Failed");
                    clicked.next('.bestaetigung').show(500);
                    clicked.next('.bestaetigung').delay(1000).hide(0);
                }
            }
            })
        }
    });

    // Updates footer in case ietd is stopped or started
    setInterval(reloadfooter, (5 * 1000));

    // ajax requests for deletelun
    deleteluniqnselection.on('change', function () {
        if (deleteluniqnselection.find('option:selected').val() != "default") {
            var data = {
                "iqn": deleteluniqnselection.find('option:selected').val()
            };

            doajax("/phpietadmin/targets/deletelun", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    deletelunluns = $('#deletelunluns');

                    deletelunluns.html(request.responseText);

                    if(deletelunluns.is(':hidden')) {
                        deletelunluns.show();
                    }
                }
            });
        } else {
            $('#deletelunluns').hide();
        }
    });

/* Document ready close */
});