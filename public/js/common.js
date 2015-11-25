requirejs.config({
    baseUrl: "/phpietadmin/js",
    paths: {
        jquery: 'lib/jquery-2.1.4',
        jqueryui: 'lib/jquery-ui',
        jqueryui_slider: 'lib/jquery-ui-slider-pips',
        bootstrap: 'lib/bootstrap.amd',
        filtertable: 'lib/jquery.filtertable.amd',
        qtip: 'lib/jquery.qtip',
        imagesloaded: 'lib/imagesloaded.pkg.amd',
        sweetalert: 'lib/sweetalert',
        mylibs: 'lib/mylibs',
        blockUI: 'lib/jquery.blockUI',
        once: 'lib/once',
        bootstraptable: 'lib/bootstrap-table',
        touchspin: 'lib/jquery.bootstrap-touchspin.amd',
        domReady: 'lib/domReady',
        pingjs: 'lib/pingjs.amd',
        nprogress: 'lib/nprogress',
        hideShowPassword: 'lib/hideShowPassword',
        vibrate: 'lib/vibrate'
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

            if (window.location.hash !== '') {
                // Remove iqn in Target -> Configure menu
                path = path.substr(0, path.lastIndexOf('/'));
            }

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