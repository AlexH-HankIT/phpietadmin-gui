function _(str) {
    return document.getElementById(str)
}

function send_deletelun() {
    $(function () {
        deleteluniqnselection = $('#deleteluniqnselection');
        deletelunlunselection = $('#deletelunlunselection');

        var data = {
            "iqn": deleteluniqnselection.find('option:selected').val(),
            "lun": deletelunlunselection.find('option:selected').val(),
            "path": deletelunlunselection.find('option:selected').next().val()
        };

        doajax("/phpietadmin/targets/deletelun", data);

        request.done(function () {
            if (request.readyState == 4 && request.status == 200) {
                _("deleteluncontent").innerHTML = request.responseText;
            }
        })
    })
}

function updateTextInput(newValue) {
    _("sizefield").value=newValue;
}

function validateinputnotdefault(id, message) {
    if(_(id).value == _("default").value) {
        alert(message);
        return false;
    }
}

function validatelvinput(newValue, freesize) {
    if (newValue == 0) {
        alert("Error - The size cannot be zero!");
        updateTextInput(1);
        _("rangeinput").value = 1;
    } else {
        if (newValue <= freesize) {
            _("rangeinput").value = newValue;
            _("range").innerHTML = newValue;
        } else {
            alert("Error - The volume group has only " + freesize + "G space left");
            updateTextInput(freesize);
            _("rangeinput").value = freesize;
            _("range").innerHTML = freesize;
        }
    }
}

function reloadfooter() {
    var pathname = window.location.pathname;

    if (pathname !== "/phpietadmin/auth/login") {
        doajax("/phpietadmin/service/status");

        request.done(function () {
            if (request.readyState == 4 && request.status == 200) {
                _("ietdstatus").innerHTML = request.responseText;
            }
        });
    }
}

function validatemaplun() {
    if(_('target').value == _("default").value) {
        alert('Error - Please select a target!');
        return false;
    } else if (_('logicalvolume').value == _("default").value) {
        alert('Error - Please select a volume!');
        return false;
    } else {
        var data = {
            "target": $('#target').val(),
            "type": $('#type').find('option:selected').val(),
            "mode": $('#mode').find('option:selected').val(),
            "path": $('#logicalvolume').val()
        };

        request = doajax("/phpietadmin/targets/maplun", data);

        request.done(function() {
            if (request.readyState == 4 && request.status == 200) {
                _("mapluncontent").innerHTML = request.responseText;
            }
        });
    }
}

function validatedeletetarget() {
    if(_('targetdelete').value == _("default").value) {
        alert('Error - Please select a target!');
        return false;
    } else {
        var data = {
            "target": $('#targetdelete').find('option:selected').val()
        };

        request = doajax("/phpietadmin/targets/deletetarget", data);

        request.done(function() {
            if (request.readyState == 4 && request.status == 200) {
                _("targetdeletecontent").innerHTML = request.responseText;
            }
        });
    }
}

// ajax request
function doajax(url, data) {
    // not every request contains data
    if (typeof data === 'undefined') {
        return request = $.ajax({
            url: url,
            type: "post"
        });
    } else {
        return request = $.ajax({
            url: url,
            type: "post",
            data: data
        });
    }
}

// ajax call to send the selected volume group to the server
function showlvinput(str) {
    var data = {
        "vg": str
    };

    doajax("/phpietadmin/lvm/add", data);

    request.done(function() {
        if (request.readyState == 4 && request.status == 200) {
            _("lv").innerHTML = request.responseText;
        }
    });
}