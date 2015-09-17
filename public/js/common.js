requirejs.config({
    baseUrl: "/phpietadmin/js",
    paths: {
        jquery: 'lib/jquery-2.1.3.min',
        jqueryui: 'lib/jquery-ui.min',
        jqueryui_slider: 'lib/jquery-ui-slider-pips',
        bootstrap: 'lib/bootstrap.min.amd',
        filtertable: 'lib/jquery.filtertable.min.amd',
        qtip: 'lib/jquery.qtip.min',
        imagesloaded: 'lib/imagesloaded.pkg.min.amd',
        sweetalert: 'lib/sweetalert.min',
        mylibs: 'lib/mylibs',
        sha256: 'lib/sha256.min',
        highchart: 'lib/highcharts.amd',
        blockUI: 'lib/jquery.blockUI.min',
        once: 'lib/once',
        bootstraptable: 'lib/bootstrap-table',
        touchspin: 'lib/jquery.bootstrap-touchspin.min.amd'
    },
    shim: {
        jqueryui: {
            export:"$" ,
            deps: ['jquery']
        },
        jqueryui_slider: {
            export:"$" ,
            deps: ['jqueryui']
        }
    }
});

define(['jquery', 'qtip', 'filtertable', 'mylibs', 'sweetalert', 'bootstrap', 'blockUI', 'once', 'bootstraptable'], function($, qtip, filterTable, mylibs, swal) {
    var methods;

    return methods = {
        common: function() {
            $(function() {
                // reload footer first time
                mylibs.reloadfooter();

                // check if server is alive
                var uiBlocked = false;
                var mainmenu = $('#mainmenu');
                var footer = $('#footer');

                setInterval(function() {
                    $.ajax({
                    type: 'post',
                    cache: false,
                    url: '/phpietadmin/connection/check_server_online',
                    timeout: 1000,
                    success: function(data) {
                        if (data == true) {
                            if (uiBlocked == true) {
                                uiBlocked = false;
                                $.unblockUI();
                                mainmenu.show();
                                footer.show();
                            }
                        }
                    }, error: function(data) {
                        if (data != true) {
                            if (uiBlocked == false) {
                                uiBlocked = true;
                                mainmenu.hide();
                                footer.hide();

                                $.blockUI({
                                    message: $('#offlinemessage'),
                                    css: {
                                        border: 'none',
                                        padding: '15px',
                                        backgroundColor: '#222',
                                        opacity: .5,
                                        color: '#fff'
                                    }
                                });
                            }
                        }
                    }
                    })
                }, 2000);

                // Updates footer in case ietd is stopped or started
                // it also reloads the page, if the session terminates
                setInterval(mylibs.reloadfooter, (11000));
                setInterval(mylibs.check_session_expired, (15000));

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
        load_workspace_event_handler: function() {
            // load workspace and perform error handling
            $(document).once('click', '.workspacetab', function () {
                var $this = $(this);
                return mylibs.load_workspace($this.attr('href'), $this);
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
        }
    };
});