function showMenu() {
    document.getElementById("nav01").innerHTML =
        "<ul id='menu'>" +
        "<li><a href='/phpietadminv02/home'>Home</a></li>" +
        "<li><a href='/phpietadminv02/overview'>Overview</a></li>" +
        "<li><a href='/phpietadmin/targets/targets.php'>Targets</a></li>" +
        "<li><a href='/phpietadmin/allow/initiators/allow.php'>Allow</a></li>" +
        "<li><a href='/phpietadmin/lvm/lvm.php'>LVM</a></li>" +
        "<li><a href='/phpietadmin/service.php'>Service</a></li>" +
        "</ul>";
}

function showValue(newValue, freesize) {
    if(newValue <= freesize) {
        document.getElementById("rangeinput").value=newValue;
        document.getElementById("range").innerHTML=newValue;
    } else {
        alert("Error - The volume group has only " + freesize + "G space left");
        updateTextInput(freesize);
        document.getElementById("range").innerHTML=freesize;
    }
}

function updateTextInput(newValue) {
    document.getElementById("sizefield").value=newValue;
}