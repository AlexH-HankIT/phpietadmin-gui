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
        highchart: 'lib/highcharts.amd',
        blockUI: 'lib/jquery.blockUI.min',
        once: 'lib/once',
        bootstraptable: 'lib/bootstrap-table',
        touchspin: 'lib/jquery.bootstrap-touchspin.min.amd',
        domReady: 'lib/domReady.min',
        pingjs: 'lib/pingjs.min.amd',
        nprogress: 'lib/nprogress'
    },
    shim: {
        jqueryui: {
            export: "$",
            deps: ['jquery']
        },
        jqueryui_slider: {
            export: "$",
            deps: ['jqueryui']
        }
    }
});

define(['jquery', 'qtip', 'filtertable', 'mylibs', 'sweetalert', 'pingjs', 'nprogress', 'bootstrap', 'blockUI', 'once', 'bootstraptable'], function ($, qtip, filterTable, mylibs, swal, pingjs, nprogress) {
    var methods;

    return methods = {
        common: function () {
            // check if server is alive
            var uiBlocked = false,
                main_menu = $('#mainmenu'),
                footer = $('footer'),
                path = window.location.pathname;

            setInterval(function () {
                pingjs.ping('/phpietadmin/connection/check_server_online', 0.3).then(function(delta) {
                    if (uiBlocked === true) {
                        uiBlocked = false;
                        $.unblockUI();
                        main_menu.show();
                        footer.show();
                    }
                }).catch(function() {
                    if (uiBlocked === false) {
                        uiBlocked = true;
                        main_menu.hide();
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
                });
            }, 2000);

            setInterval(mylibs.check_session_expired, (15000));

            // Select active menu element, when page is loaded manually
            path = path.replace(/\/$/, '');
            path = decodeURIComponent(path);

            $('#main_menu_bar').find('a').each(function () {
                var $this = $(this);
                if ($this.attr('href') !== undefined) {
                    if ($this.attr('href') === path) {
                        $this.closest('li').addClass('active').parents().addClass('active');
                    }
                }
            });

            $(document).ajaxStart(function() {
                nprogress.start();
            });

            $(document).ajaxComplete(function() {
                nprogress.done();
            });

        },
        load_workspace_event_handler: function () {
            // load workspace and perform error handling
            $("#main_menu_bar").once("click", "a", function () {
                var $this = $(this),
                    link = $this.attr('href');
                if (link !== '/phpietadmin/auth/logout') {
                    return mylibs.load_workspace(link, $this);
                } else {
                    return true;
                }
            });
        },
        add_event_handler_shutdown: function () {
            $('#menushutdownbutton').once('click', function () {
                swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, do it!",
                        closeOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            type: 'post',
                            url: '/phpietadmin/service/hold',
                            data: {
                                'action': 'shutdown'
                            }
                        });
                    });
                return false;
            });
        },
        add_event_handler_reboot: function () {
            $('#menurebootbutton').once('click', function () {
                swal({
                        title: "Are you sure?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, do it!",
                        closeOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            type: 'post',
                            url: '/phpietadmin/service/hold',
                            data: {
                                'action': 'reboot'
                            }
                        });
                    });
                return false;
            });
        }
    };
});
