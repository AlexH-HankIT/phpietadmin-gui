function showMenu() {
    document.getElementById("nav01").innerHTML =
        "<ul id='menu'>" +
        "<li><a href='/phpietadminv02/home'>Home</a></li>" +
        "<li><a href='/phpietadminv02/overview'>Overview</a></li>" +
        "<li><a href='/phpietadminv02/targets'>Targets</a></li>" +
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

function loadnext(file) {
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",file,true);
    xmlhttp.send();
}


function handleOnChange(dd1) {
  var idx = dd1.selectedIndex;
  var val = dd1[idx].text;
  var par = document.forms["vgselect"];
  var parelmts = par.elements;
  var prezsel = parelmts["prez"];
  var country = val;

  if (country != "Select country") {
   var directory = ""+document.location;

   directory = directory.substr(0, directory.lastIndexOf('/'));

   Http.get({
		url: "./" +  country + ".txt",
		callback: fillPrez,
		cache: Http.Cache.Get
	}, [prezsel]);
  }

}

$('#selectVolume li').click(function(){
    alert("TEST");
    //data = "TEST";
    //$.ajax({
    //    type:    'post',
    //    url:     'phpietadminv02/index.php',
    //    data:    data,
    //    success: function(result) {
    //        alert(result);
    //    }
    //})
});

function fillPrez(xmlreply, prezelmt) {
  if (xmlreply.status == Http.Status.OK) {
   var prezresponse = xmlreply.responseText;
   var prezar = prezresponse.split("|");
   prezelmt.length = 1;
   prezelmt.length = prezar.length;

   for (o=1; o < prezar.length; o++) {
     prezelmt[o].text = prezar[o];
   }
  }
  else {
   alert("Cannot handle the Ajax call.");
  }

}