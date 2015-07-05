requirejs.config({
    baseUrl: "/phpietadmin/js",
    paths: {
        jquery: 'lib/jquery-2.1.3.min',
        bootstrap: 'lib/bootstrap.min.amd',
        filtertable: 'lib/jquery.filtertable.min.amd',
        qtip: 'lib/jquery.qtip.min',
        imagesloaded: 'lib/imagesloaded.pkg.min.amd',
        sweetalert: 'lib/sweetalert.min',
        mylibs: 'lib/mylibs',
        sha256: 'lib/sha256.min',
        highchart: 'lib/highcharts.amd'
    }
});

define(['jquery', 'qtip', 'filtertable', 'mylibs', 'sweetalert', 'bootstrap'], function($, qtip, filterTable, mylibs, swal) {
    var methods;

    return methods = {
        common: function() {
            $(function() {
                // Enable filter table plugin
                $('.searchabletable').filterTable({minRows:0});

                // Updates footer in case ietd is stopped or started
                // it also reloads the page, if the session terminates
                setInterval(mylibs.reloadfooter, (30000));

                // Select active menu element, when page is loaded manually
                var path = window.location.pathname;
                path = path.replace(/\/$/, "");
                path = decodeURIComponent(path);

                $('.nav a').each(function(){
                    if ($(this).attr('href') != undefined) {
                        if ($(this).attr('href') == path) {
                            $(this).closest('li').addClass('active');
                            $(this).closest('li').parents().addClass('active');
                        }
                    }
                });
            });
        },
        add_event_handler_workspacetab: function() {
            $(document).ready(function(){
                $(document).off('click', '.workspacetab');
                $(document).on('click', '.workspacetab', function(e) {

                    mylibs.loadworkspace($(this), $(this).attr('href'));

                    // stop link
                    e.preventDefault();
                    return false;
                });
            });
        },
        add_event_handler_shutdown: function() {
            $(document).ready(function(){
                $(document).off('click', '#menushutdownbutton');
                $(document).on('click', '#menushutdownbutton', function(e) {
                        swal({
                                title: "Are you sure?",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, do it!",
                                closeOnConfirm: false
                            },
                            function(){
                                var data = {
                                    "action": 'shutdown'
                                };

                                var request = mylibs.doajax('/phpietadmin/service/hold', data);

                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: 'It can take up to 20 seconds till the server shuts down!'
                                });
                            });
                    e.preventDefault();
                });
            });
        },
        add_event_handler_reboot: function() {
            $(document).ready(function(){
                $(document).off('click', '#menurebootbutton');
                $(document).on('click', '#menurebootbutton', function(e) {
                    swal({
                            title: "Are you sure?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, do it!",
                            closeOnConfirm: false
                        },
                        function(){
                            var data = {
                                "action": 'reboot'
                            };

                            var request = mylibs.doajax('/phpietadmin/service/hold', data);

                            swal({
                                title: 'Success',
                                type: 'success',
                                text: 'It can take up to 20 seconds till the server reboots!'
                            });
                        });
                    e.preventDefault();
                });
            });
        },
        sticky_button: function() {
            $(document).ready(function(){

            });
        }
    };
});