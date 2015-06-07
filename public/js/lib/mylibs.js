define(function() {
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
                Methods.doajax("/phpietadmin/service/status");

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        $("#ietdstatus").html(request.responseText);
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
        checkversion: function() {
            var installedversion = $('#phpietadminversion').text();

            var request = $.ajax({
                url: '/phpietadmin/dashboard/get_version',
                type: "post",
                async: false
            });

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText == installedversion) {
                        val = true;
                    } else {
                        val = request.responseText;
                    }
                }
            });

            return val;
        }
    }
});

