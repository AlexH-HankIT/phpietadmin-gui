define(function() {
    var Methods;
    return Methods = {
        // ajax request
        doajax: function(url, data) {
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
        },
        reloadfooter: function() {
            var pathname = window.location.pathname;

            if (pathname !== "/phpietadmin/auth/login") {
                request = Methods.doajax("/phpietadmin/service/status");

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == false) {
                            // if response is false, we are not logged in
                            // reload the page to display the login form
                            window.location.reload();
                        } else {
                            $("#ietdstatus").html(request.responseText);
                        }
                    }
                });
            }
        },
        validateipv4: function(ipv4) {
                return (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipv4))
        },
        validateipv4network: function(net) {
            // Trennen von Netz-IP und Subnetmask
            net = net.split("/");
            // Netzwerk-IP
            netip = net[0];
            // Subnetmask
            mask = net[1];
            // Segmente aufteilen
            seg = netip.split("\.");
            // Hostanteil
            hostanteil = 32-mask;
            // Hostanteil im letzten Segment
            hostanteilLetztesSegment = hostanteil%8;
            // Hosts im letzten Segment
            hostsLetztesSegment = Math.pow(2, hostanteilLetztesSegment);
            // Auswahl Segment
            auswahlSeg = parseInt(mask/8);

            if( seg[auswahlSeg]%hostsLetztesSegment == 0 ) {
                // Alle Segmente hinter der Mask muessen Null sein
                allNull = true;
                for (var i=3; i > auswahlSeg; i--) {
                    if( seg[i] != 0 ) {
                        allNull = false;
                    }
                }
                return allNull;
            }
            else {
                return false;
            }
        },
        generatePassword: function()  {
            var length = 16,
                charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
                retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            return retVal;
        },
        loadconfiguretargetbody: function(clicked, url, data) {
            if (typeof data === 'undefined') {
                // if type is undefined, not data will be passed
                Methods.doajax(url);
            } else if (typeof data === 'string' ) {
                // if type is string an array will be created an posted
                var array = {
                    iqn: data
                };

                Methods.doajax(url, array);
            } else {
                // else data is already an array
                Methods.doajax(url, data);
            }

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    $('#configuretargetbody').html(request.responseText);
                    $('#configuretargetmenu').find('ul').children('li').removeClass('active');
                    clicked.parents('li').addClass('active');
                }
            });
        },
        loadworkspace: function(clicked, url) {
            Methods.doajax(url);

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    $('#workspace').html(request.responseText);
                    $('#mainmenu').find('ul').children('li').removeClass('active');
                    clicked.parents('li').addClass('active');
                }
            });
        }
    }
});