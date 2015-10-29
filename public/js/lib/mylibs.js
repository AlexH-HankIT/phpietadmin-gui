define(['jquery', 'qtip', 'filtertable', 'sweetalert', 'blockUI', 'nprogress', 'bootstrap'], function($, qtip, filterTable, swal, blockUI, nprogress) {
    var methods;
    return methods = {
        reloadfooter: function() {
            if (window.location.pathname !== "/phpietadmin/auth/login") {
                $.ajax({
                    url: '/phpietadmin/connection/status',
                    data: {
                        "servicename": 'iscsitarget'
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data) {
                        if (data['code'] == 0) {
                            $("#ietdstatus").html('<a class = "navbar-btn btn-success btn pull-left"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> ietd running</a>');
                        } else {
                            $("#ietdstatus").html('<a class = "navbar-btn btn-danger btn pull-left"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> ietd not running</a>');
                        }
                    },
                    error: function () {
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
                    success: function (data) {
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
            var netip = net[0],
                mask = net[1],
                seg = netip.split("\."),
                hostanteil = 32 - mask,
                hostanteilLetztesSegment = hostanteil % 8,
                hostsLetztesSegment = Math.pow(2, hostanteilLetztesSegment),
                auswahlSeg = parseInt(mask / 8);

            if (seg[auswahlSeg] % hostsLetztesSegment == 0) {
                // Alle Segmente hinter der Mask muessen Null sein
                var allNull = true;
                for (var i = 3; i > auswahlSeg; i--) {
                    if (seg[i] != 0) {
                        allNull = false;
                    }
                }
                return allNull;
            } else {
                return false;
            }
        },
        generatePassword: function() {
            var length = 16,
                charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
                retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i) {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            return retVal;
        },
        load_configure_target_body: function(link, clicked) {
            $('#configure_target_body').remove();
            nprogress.start();

            if (clicked !== undefined && clicked != '') {
                $('#configure_target_menu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            $('#configure_target_body_wrapper').load(link, {iqn: $('#target_selector').find("option:selected").val()}, function (response, status) {
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
                }
                nprogress.done();
            });
            return false;
        },
        load_lvm_target_body: function (link, clicked) {
            var logical_volume_selector_selected = $('#logical_volume_selector').find("option:selected");
            $('#configure_lvm_body').remove();
            nprogress.start();

            if (clicked !== undefined && clicked != '') {
                $('#configure_lvm_menu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            $('#configure_lvm_body_wrapper').load(link, {lv: logical_volume_selector_selected.attr('data-lv'), vg: logical_volume_selector_selected.attr('data-vg')}, function (response, status) {
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
                }
                nprogress.done();
            });
            return false;
        },
        load_data: function(link) {
            nprogress.start();
            $('#data').html('').load(link, {iqn: $('#target_selector').find("option:selected").val()}, function (response, status) {
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
                }
                nprogress.done();
            });
            return false;
        },
        is_int: function(value) {
            return (parseFloat(value) == parseInt(value)) && !isNaN(value);
        },
        check_service_status: function(row) {
            var $this_row = row.closest('tr');
            var service_status = $this_row.find('.servicestatus');
            var service_name = $this_row.find('.servicename');

            $.ajax({
                url: '/phpietadmin/connection/status',
                data: {
                    "servicename": service_name.text()
                },
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    if (data['code'] !== 0) {
                        service_status.removeClass('label label-success').text('Not running').addClass('label label-danger');
                    } else {
                        service_status.removeClass('label label-danger').text('Running').addClass('label label-success');
                    }
                }
            });
        },
        load_workspace: function (link, clicked) {
            // select menu
            if (clicked !== undefined && clicked != '') {
                $('#mainmenu').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            nprogress.start();

            // remove the previously loaded workspace (only if the site was called directly, via F5 for example)
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
                } else {
                    window.history.pushState({path: link}, '', link);
                }
                nprogress.done();
            });
            return false;
        },
        select_all_checkbox: function(checkbox) {
            checkbox.once('click', function() {
                $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
            });
        }
    }
});