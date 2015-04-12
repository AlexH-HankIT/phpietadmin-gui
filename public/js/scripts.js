function showMenu() {
    document.getElementById("nav01").innerHTML =
        "<ul id='menu'>" +
        "<li><a href='/phpietadminv02/home'>Home</a></li>" +
        "<li><a href='/phpietadminv02/overview'>Overview</a></li>" +
        "<li><a href='/phpietadminv02/targets'>Targets</a></li>" +
        "<li><a href='/phpietadminv02/allow'>Allow</a></li>" +
        "<li><a href='/phpietadminv02/lvm'>LVM</a></li>" +
        "<li><a href='/phpietadmin/service.php'>Service</a></li>" +
        "</ul>";
}

function validatelvinput(newValue, freesize) {
    if (newValue == 0) {
        alert("Error - The size cannot be zero!");
        //document.getElementById("sizefield").value=newValue;
        updateTextInput(1);
        document.getElementById("rangeinput").value = 1;
    } else {
        if (newValue <= freesize) {
            document.getElementById("rangeinput").value = newValue;
            document.getElementById("range").innerHTML = newValue;
        } else {
            alert("Error - The volume group has only " + freesize + "G space left");
            updateTextInput(freesize);
            document.getElementById("rangeinput").value = freesize;
            document.getElementById("range").innerHTML = freesize;
        }
    }
}

function validateinputnotempty(id) {
    if(document.getElementById(id).value == "") {
        alert("Field " + id + " is empty");
        return false;
    }
}

function updateTextInput(newValue) {
    document.getElementById("sizefield").value=newValue;
}

function validatevginput() {
    if(document.getElementById("vg").value == document.getElementById("default").value) {
        alert("Please select a volume group");
        return false;
    }
}