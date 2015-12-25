define(['jquery', 'qtip', 'filtertable', 'sweetalert', 'blockUI', 'bootstrap'], function($, qtip, filterTable, swal, blockUI) {
    return {
        /**
         * @url http://stackoverflow.com/questions/19999388/check-if-user-is-using-ie-with-jquery/21712356#21712356
         * @returns {*}
         */
        detectIE: function () {
            var ua = window.navigator.userAgent;

            var msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older => return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
            }

            var trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11 => return version number
                var rv = ua.indexOf('rv:');
                return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
            }

            var edge = ua.indexOf('Edge/');
            if (edge > 0) {
                // Edge (IE 12+) => return version number
                return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
            }

            // other browser
            return false;
        },
        check_session_expired: function() {
            if (window.location.pathname !== '/phpietadmin/auth/login') {
                $.ajax({
                    url: '/phpietadmin/connection/check_session_expired',
                    type: 'post',
                    global: false,
                    success: function (data) {
                        if (data == '') {
                            // reload the page to display the login form
                            window.location.reload();
                        }
                    }
                });
            }
        },
        validateIpv4: function(ipv4) {
            return (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipv4))
        },
        validateIpv6: function(ipv6) {
            return (/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/.test(ipv6));
        },
        validateIpv4Network: function(net) {
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
        load_lvm_target_body: function (link, clicked) {
            var logical_volume_selector_selected = $('#logical_volume_selector').find("option:selected");
            $('#configure_lvm_body').remove();

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
            });
            return false;
        },
        is_int: function(value) {
            return (parseFloat(value) == parseInt(value)) && !isNaN(value);
        },
        check_service_status: function(row) {
            var $this_row = row.closest('tr'),
                service_status = $this_row.find('.servicestatus');

            $.ajax({
                url: '/phpietadmin/connection/status',
                data: {
                    "servicename": $this_row.find('.servicename').text()
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
            var _this = this;

            // select menu
            if (clicked !== undefined && clicked !== '') {
                $('div.navHeaderCollapse').find('ul').children('li').removeClass('active');
                clicked.parents('li').addClass('active');
            }

            // remove the previously loaded workspace (only if the site was called directly, via F5 for example)
            // content loaded via ajax is inside the #workspace_wrapper div
            // normally we wouldn't need this
            // just load everything into the workspace div and let the load() function only insert the container div
            // unfortunately the load() function strips all <script> tags if its called with a selector
            // this is a workaround for this
            $('.workspace').remove();

            // ignore the workspace and load only the container class
            var $workspace_wrapper = $('#workspace_wrapper');

            $workspace_wrapper.fadeOut('fast', function(){
                $workspace_wrapper.load(link, function (response, status) {
                    $workspace_wrapper.fadeIn('fast');
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
                        // Hide menu onchange on devices <768px
                        if (_this.getSize() === 'visible-xs') {
                            $('.navbar-toggle').click();
                        }
                        window.history.pushState({path: link}, '', link);
                    }
                });
            });
            return false;
        },
        getSize: function () {
            /* Use the #mq-detector span to determin the device size we are on */
            var currMqIdx = undefined,
                mqDetector = $("#mq-detector"),
                mqSelectors = [
                mqDetector.find(".visible-xs"),
                mqDetector.find(".visible-sm"),
                mqDetector.find(".visible-md"),
                mqDetector.find(".visible-lg")
            ];

            for (var i = 0; i <= mqSelectors.length; i++) {
                if (mqSelectors[i].is(":visible")) {
                    if (currMqIdx != i) {
                        currMqIdx = i;
                        return mqSelectors[currMqIdx].attr("class");
                    }
                }
            }
        },
        select_all_checkbox: function(checkbox) {
            checkbox.once('click', function() {
                $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
            });
        },
        loadConfigureTargetBody: function (body, iqnVal) {
            $('#configureTargetBody').fadeOut('fast', function(){
                $(this).load('/phpietadmin/targets/configure/' + iqnVal + '/' + body.substring(1), function (response, status) {
                    $(this).fadeIn('fast');
                    if (status === 'error') {
                        // Display error message
                        $(this).html(
                            "<div class='container'>" +
                            "<div class='alert alert-warning' role='alert'>" +
                            '<h3 align="center">Sorry, can\'t load the body!</h3>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                });
            });
        }
    }
});