/* Docuemt ready open */
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

        if (iqninput.val() == def) {
            iqninput.addClass("focusedInputerror");
            return false;
        } else {
            var data = {
                "name": iqninput.val()
            };

            doajax("/phpietadmin/targets/addtarget", data);

            request.done(function() {
                if (request.readyState == 4 && request.status == 200) {
                    $('#addtargetinput').html(request.responseText);
                }
            });
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