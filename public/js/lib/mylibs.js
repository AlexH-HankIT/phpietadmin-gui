define(['jquery', 'qtip', 'filtertable', 'mylibs', 'sweetalert', 'blockUI', 'bootstrap'], function($, qtip, filterTable, mylibs, swal, blockUI) {
    var Methods;
    return Methods = {
        reloadfooter: function() {
            if (window.location.pathname !== "/phpietadmin/auth/login") {

                $.ajax({
                    url: '/phpietadmin/connection/status',
                    data:  {
                        "servicename" : 'iscsitarget'
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function(data) {
                        if (data['code'] == 0) {
                            $("#ietdstatus").html('<a class = "navbar-btn btn-success btn pull-left"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> ietd running</a>');
                        } else {
                            $("#ietdstatus").html('<a class = "navbar-btn btn-danger btn pull-left"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> ietd not running</a>');
                        }
                    },
                    error: function() {
                        $("#ietdstatus").html('<a class = "navbar-btn btn-warning btn pull-left"><span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span> ietd status unkown</a>');
                    }
                });
            }
        },
        check_session_expired: function() {
            if (window.location.pathname !== '/phpietadmin/auth/login') {
                $.ajax({
                    url: '/phpietadmin/connection/check_session_expired',
                    type: 'post',
                    success: function(data) {
                        if (data == '') {
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
        load_configure_target_body: function(link, clicked) {
            var ajaxloader = $('#ajaxloader');
            var ajax_error_sign = $('#ajax_error_sign');
            var iqn = $('#target_selector').find("option:selected").val();

            $('#configure_target_body').remove();

            ajax_error_sign.hide();
            ajaxloader.show();

            if (clicked !== undefined && clicked != '') {
                $('#configure_target_menu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            $('#configure_target_body_wrapper').load(link, {iqn: iqn}, function (response, status) {
                if (status == 'error') {
                    $(this).html("<div id='configure_target_body'>" +
                    "<div class='container'>" +
                    "<div class='alert alert-warning' role='alert'>" +
                    "<h3 align='center'>" +
                    response +
                    "</h3>" +
                    "</div>" +
                    '</div>' +
                    '</div>');

                    ajax_error_sign.show();
                }
            });

            ajaxloader.delay(10).hide(10);

            return false;
        },
        load_lvm_target_body: function(link, clicked) {
            var ajaxloader = $('#ajaxloader');
            var ajax_error_sign = $('#ajax_error_sign');
            var logical_volume_selector = $('#logical_volume_selector');
            var lv = logical_volume_selector.find("option:selected").attr('data-lv');
            var vg = logical_volume_selector.find("option:selected").attr('data-vg');

            $('#configure_lvm_body').remove();

            ajax_error_sign.hide();
            ajaxloader.show();

            if (clicked !== undefined && clicked != '') {
                $('#configure_lvm_menu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            $('#configure_lvm_body_wrapper').load(link, {lv: lv, vg: vg}, function (response, status) {
                if (status == 'error') {
                    $(this).html("<div id='configure_lvm_body'>" +
                    "<div class='container'>" +
                    "<div class='alert alert-warning' role='alert'>" +
                    "<h3 align='center'>" +
                    response +
                    "</h3>" +
                    "</div>" +
                    '</div>' +
                    '</div>');

                    ajax_error_sign.show();
                }
            });

            ajaxloader.delay(10).hide(10);

            return false;
        },
        load_data: function(link) {
            var ajax_loader = $('#ajaxloader');
            var ajax_error_sign = $('#ajax_error_sign');
            var data = $('#data');
            var iqn = $('#target_selector').find("option:selected").val();

            // clean div
            data.html('');

            ajax_error_sign.hide();
            ajax_loader.show();

            data.load(link, {iqn: iqn}, function (response, status) {
                if (status == 'error') {
                    $(this).html("<div id='configure_target_control'>" +
                    "<div class='container'>" +
                    "<div class='alert alert-warning' role='alert'>" +
                    "<h3 align='center'>" +
                    response +
                    "</h3>" +
                    "</div>" +
                    '</div>' +
                    '</div>');

                    ajax_error_sign.show();
                }
            });

            ajax_loader.delay(10).hide(10);

            return false;
        },
        is_int: function(value) {
            return (parseFloat(value) == parseInt(value)) && !isNaN(value);
        },
        check_service_status: function(row) {
            var service_status = row.closest('tr').find('.servicestatus');
            var service_name = row.closest('tr').find('.servicename');

            $.ajax({
                url: '/phpietadmin/connection/status',
                data:  {
                    "servicename": service_name.text()
                },
                dataType: 'json',
                type: 'post',
                success: function(data) {
                    if (data['code'] !== 0) {
                        if (service_status.hasClass('label label-success')) {
                            service_status.removeClass('label label-success')
                        }
                        service_status.text('Not running');
                        service_status.addClass('label label-danger')
                    } else {
                        if (service_status.hasClass('label label-danger')) {
                            service_status.removeClass('label label-danger')
                        }
                        service_status.text('Running');
                        service_status.addClass('label label-success')
                    }
                }
            });
        },
        load_workspace: function (link, clicked) {
            $(document).ready(function () {
                var ajaxloader = $('#ajaxloader');
                var ajax_error_sign = $('#ajax_error_sign');

                ajax_error_sign.hide();

                // select menu
                if (clicked !== undefined && clicked != '') {
                    $('#mainmenu').find('ul').children('li').removeClass('active');
                    clicked.parents('li').addClass('active');
                }

                ajaxloader.show();

                // remove the previously loaded workspace (only we the site was called directly, via F5 for example)
                // content loaded via ajax is inside the #workspace_wrapper div
                // normally we wouldn't need this
                // just load everything into the workspace div and let the load() function only insert the container div
                // unfortunately the load() function strips all <script> tags if its called with a selector
                // this is a workaround for this
                $('#workspace').remove();

                // ignore the workspace and load only the container class
                $('#workspace_wrapper').load(link, function (response, status) {
                    if (status == 'error') {
                        $(this).html("<div id='workspace'>" +
                        "<div class='container'>" +
                        "<div class='alert alert-warning' role='alert'>" +
                        "<h3 align='center'>" +
                        response +
                        "</h3>" +
                        "</div>" +
                        '</div>' +
                        '</div>');

                        ajax_error_sign.show();
                    }
                });

                window.history.pushState({path: link}, '', link);

                ajaxloader.delay(10).hide(10);
            });
            return false;
        }
    }
});