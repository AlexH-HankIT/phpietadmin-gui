function _(str) {
    return document.getElementById(str)
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

function updateTextInput(newValue) {
    _("sizefield").value=newValue;
}

function validateinputnotdefault(id, message) {
    if(_(id).value == _("default").value) {
        alert(message);
        return false;
    }
}

$(function () {
    setNavigation();
});

function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".nav a").each(function () {
        var href = $(this).attr('href');
        if (path.substring(0, href.length) === href) {
            $(this).closest('li').addClass('active');
        }
    });
}

function showlvinput(str) {
    var data = {
        "vg": str
    };

    request = $.ajax({
        url: "/phpietadmin/lvm/add",
        type: "post",
        data: data
    });

    request.done(function() {
        if (request.readyState == 4 && request.status == 200) {
            _("lv").innerHTML = request.responseText;
        }}
    );
}

function validatemaplun() {
    if(_('target').value == _("default").value) {
        alert('Error - Please select a target!');
        return false;
    } else if (_('logicalvolume').value == _("default").value) {
        alert('Error - Please select a volume!');
        return false;
    }
}

function validate_addtarget(iqn) {
    if (_('name').value == iqn) {
        alert('Please type a complete iqn!');
        return false;
    } else {
        return true;
    }
}