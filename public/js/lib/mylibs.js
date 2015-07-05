define(function() {
    var Methods;
    return Methods = {
        // ajax request
        doajax: function(url, data) {
            var request;
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
            if (window.location.pathname !== "/phpietadmin/auth/login") {
                var data = {
                    "servicename" : 'iscsitarget'
                };

                var request = Methods.doajax('/phpietadmin/service/status', data);

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        if (request.responseText == 0) {
                            $("#ietdstatus").html('<a class = "navbar-btn btn-success btn pull-left"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> ietd running</a>');
                        } else if (request.responseText == 3) {
                            $("#ietdstatus").html('<a class = "navbar-btn btn-danger btn pull-left"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> ietd not running</a>');
                        } else {
                            // if response is false, we are not logged in
                            // reload the page to display the login form
                            window.location.reload();
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
            var netip = net[0];
            // Subnetmask
            var mask = net[1];
            // Segmente aufteilen
            var seg = netip.split("\.");
            // Hostanteil
            var hostanteil = 32-mask;
            // Hostanteil im letzten Segment
            var hostanteilLetztesSegment = hostanteil%8;
            // Hosts im letzten Segment
            var hostsLetztesSegment = Math.pow(2, hostanteilLetztesSegment);
            // Auswahl Segment
            var auswahlSeg = parseInt(mask/8);

            if( seg[auswahlSeg]%hostsLetztesSegment == 0 ) {
                // Alle Segmente hinter der Mask muessen Null sein
                var allNull = true;
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
        loadconfiguretargetbody: function(url, data, clicked) {
            var page = url.replace('/', '_');
            url = '/phpietadmin/' + url;
            var configuretargetbody = $('#configuretargetbody');

            if (clicked !== undefined && clicked != '') {
                $('#configuretargetmenu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }
            if (!configuretargetbody.hasClass(page)) {
                var request;
                if (data === undefined) {
                    // if type is undefined, not data will be passed
                    request = Methods.doajax(url);
                } else if (typeof data == 'string' ) {
                    // if type is string an array will be created an posted
                    var array = {
                        iqn: data
                    };

                    request = Methods.doajax(url, array);
                } else {
                    // else data is already an array
                    request = Methods.doajax(url, data);
                }

                request.done(function () {
                    if (request.readyState == 4 && request.status == 200) {
                        configuretargetbody.html('');
                        configuretargetbody.html(request.responseText);
                        configuretargetbody.removeClass();
                        configuretargetbody.addClass(page);
                    }
                });
            } else {
                console.log('Already loaded');
            }
        },
        loadworkspace: function(clicked, site) {
            // replace the slash in site with underscore
            // we will use this as class later
            var page = site.replace('/', '_');

            // define workspace
            var workspace = $('#workspace');

            // select menu
            if (clicked != '') {
                $('#mainmenu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            // load only if url is not identical
            if(site + '#' != window.location.pathname) {
                if (site != window.location.pathname) {
                    var ajaxloader = $('#ajaxloader');
                    ajaxloader.show();
                    var request = Methods.doajax(site);
                    request.done(function () {
                        if (request.readyState == 4 && request.status == 200) {
                            // change url
                            window.history.pushState({path: site}, '', site);

                            // delete previous loaded workspace
                            workspace.html('');

                            // delete workspaces, which are not loaded via ajax
                            $('.workspacedirect').html('');

                            // add new workspace
                            workspace.html(request.responseText);

                            // delete all classes from workspace
                            workspace.removeClass();

                            // add current loaded page as class to workspace
                            workspace.addClass(page);
                        }
                    });
                    ajaxloader.delay(10).hide(10);
                }
            }
        },
        is_int: function(value) {
            return (parseFloat(value) == parseInt(value)) && !isNaN(value);
        },
        check_service_status: function(row) {

            var data = {
                "servicename": row.text()
            };

            var request = Methods.doajax('/phpietadmin/service/status', data);

            row = row.closest('tr').find('.servicestatus');

            request.done(function () {
                if (request.readyState == 4 && request.status == 200) {
                    if (request.responseText != 0) {
                        if (row.hasClass('label label-success')) {
                            row.removeClass('label label-success')
                        }
                        row.text('Not running');
                        row.addClass('label label-danger')
                    } else {
                        if (row.hasClass('label label-danger')) {
                            row.removeClass('label label-danger')
                        }
                        row.text('Running');
                        row.addClass('label label-success')
                    }
                }
            });
        }
    }
});