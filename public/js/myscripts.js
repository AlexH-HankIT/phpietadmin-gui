// Used in views/targets/addtarget.php
// Validates the iqn input field
$(function () {
    // Save default input for later restore
    var $select = $("#iqninput");
    var data = $select.val();

    $select.keydown( function(e) {
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
});

// Focus the iqninput in views/targets/addtarget.php when the site is loaded
$(function () {
    var input = $('#iqninput');
    input.focus();
    var $thisVal = input.val();
    input.val('').val($thisVal);

    // remove error if field is clicked
    input.click(function () {
        input.removeClass("focusedInputerror");
    });
});

// Select active menu element
$(function () {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".nav a").each(function () {
        var href = $(this).attr('href');
        if (path.substring(0, href.length) === href) {
            $(this).closest('li').addClass('active');
        }
    });
});

$(function () {
    ietpermissiontargetselection = $('#ietpermissiontargetselection');
    ietpermissiontargettable = $('#ietpermissiontargettable');

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
                    _('ietpermissiontargettable').innerHTML = request.responseText;
                }
            });
        }
    });
});

// shows/hides the manual input in views/targets/mapulun.php
$(function() {
    maplun = $('#maplunmanuelinput');
    autoselection = $("#maplunautoselection");
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
});


$(function() {
    $(document).on('click', '#logicalvolumedeletebutton', function(){
        if($('#logicalvolumedeleteselection').find('option:selected').val() == $("#default").val()) {
            alert('Error - Please select a volume!');
        } else {
            var data = {
                "target": $('#logicalvolumedeleteselection').find('option:selected').val()
            };

            request = doajax("/phpietadmin/lvm/delete", data);

            request.done(function() {
                if (request.readyState == 4 && request.status == 200) {
                    _("logicalvolumedeletecontent").innerHTML = request.responseText;
                }
            });
        }
    });
});

/*
 * Configuration menu
 */
// Configuration menu enables the right data
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
$(function() {
    setInterval(reloadfooter, (5 * 1000));
});



// ajax requests for deletelun
$(function() {
    $('#deleteluniqnselection').on('change', function () {
        deleteluniqnselection = $('#deleteluniqnselection');

        if (deleteluniqnselection.find('option:selected').val() != "default") {
            var data = {
                "iqn": deleteluniqnselection.find('option:selected').val()
            };

            doajax("/phpietadmin/targets/deletelun", data);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    deletelunluns = $('#deletelunluns');

                    _('deletelunluns').innerHTML = request.responseText;
                    if(deletelunluns.is(':hidden')) { deletelunluns.show(); }
                }
            });
        } else {
            $('#deletelunluns').hide();
        }
    });
});