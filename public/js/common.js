requirejs.config({
    paths: {
        jquery: 'lib/jquery-2.1.4',
        jqueryUi: 'lib/jquery-ui',
        jqueryUiSlider: 'lib/jquery-ui-slider-pips',
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
        vibrate: 'lib/vibrate',
        clipboard: 'lib/clipboard',
        bootstrapSelect: 'lib/bootstrap-select',
        bootstrapTableExport: 'lib/bootstrap-table-export',
        tableExport: 'lib/tableExport',
        base64: 'lib/jquery.base64'
    }
});

define(['jquery', 'mylibs', 'sweetalert', 'pingjs', 'nprogress', 'bootstrap', 'blockUI', 'once'], function ($, mylibs, swal, pingjs, nprogress) {
    return {
        common: function () {
            // check if server is alive
            var uiBlocked = false,
                $mainMenu = $('div.navbar-static-top'),
                $footer = $('footer');

            // Only use pingjs in non internet explorer browsers, since ie does not support promises
            if (mylibs.detectIE() === false) {
                setInterval(function () {
                    pingjs.ping(require.toUrl('../connection/check_server_online'), 0.3).then(function(delta) {
                        if (uiBlocked === true) {
                            uiBlocked = false;
                            $.unblockUI();
                            $mainMenu.show();
                            $footer.show();
                        }
                    }).catch(function() {
                        if (uiBlocked === false) {
                            uiBlocked = true;
                            $mainMenu.hide();
                            $footer.hide();
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
            }

            setInterval(mylibs.check_session_expired, 5000);

            $(document).ajaxStart(function() {
                nprogress.start();
            });

            $(document).ajaxComplete(function() {
                nprogress.done();
            });

        },
        menu: function() {
            var $navBarRight = $('div.navHeaderCollapse'),
                path;

            /*
             * Select active menu element, when page is loaded manually
             */
            path = window.location.pathname.replace(/\/$/, '');

            // Remove iqn in Target -> Configure menu
            if (window.location.hash !== '') {
                path = path.substr(0, path.lastIndexOf('/'));
            }

            path = decodeURIComponent(path);

            $navBarRight.find('a.workspaceTab').each(function () {
                var $this = $(this),
                    link = $this.attr('href');
                if (link !== undefined) {
                    if (link === path) {
                        $this.closest('li').addClass('active').parents().addClass('active');
                    }
                }
            });

            /*
             * Add event handler for menu
             */
            $navBarRight.once('click', 'a', function () {
                var $this = $(this),
                    link = $this.attr('href');

                // Don't fire ajax for logout button
                if (link.indexOf('/auth/logout') > -1) {
                    return true;
                } else {
                    if (link !== '#') {
                        return mylibs.load_workspace(link, $this);
                    }
                }
            });

            /*
             * Add event handler for shutdown/reboot
             */
            $('#shutdown, #reboot').once('click', function () {
                var $this = $(this);
                swal({
                        title: 'Are you sure?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, do it!',
                        closeOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            type: 'post',
                            url: $this.attr('href'),
                            data: {
                                'action': $this.attr('id')
                            }
                        });
                    });
                return false;
            });
        }
    };
});