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
        highchart: 'lib/highcharts.amd',
        blockUI: 'lib/jquery.blockUI.min',
        once: 'lib/once',
        bootstraptable: 'lib/bootstrap-table',
        jqueryui: 'lib/jquery-ui'
    }
});

define(['jquery', 'qtip', 'filtertable', 'mylibs', 'sweetalert', 'bootstrap', 'blockUI', 'once', 'bootstraptable'], function($, qtip, filterTable, mylibs, swal) {
    var methods;

    return methods = {
        common: function() {
            $(function() {
                // check if server is alive
                var uiBlocked = false;
                //var ajaxloader =);
                var mainmenu = $('#mainmenu');
                var footer = $('#footer');

                setInterval(function() {
                    $.ajax({
                    type: 'post',
                    cache: false,
                    url: '/phpietadmin/connection/check_server_online',
                    timeout: 1000,
                    success: function(data, textStatus, XMLHttpRequest) {
                        if (data == 'alive') {
                            if (uiBlocked == true) {
                                uiBlocked = false;
                                $.unblockUI();
                                mainmenu.show();
                                footer.show();
                            }
                        }
                    }, error: function(data, textStatus, XMLHttpRequest) {
                        if (data != 'alive') {
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
        load_workspace: function() {
            // load workspace and perform error handling
            $(document).ready(function(){
                    $(document).once('click', '.workspacetab', function() {
                        var $this = $(this);
                        var link = $this.attr('href');
                        var ajaxloader = $('#ajaxloader');
                        var ajax_error_sign = $('#ajax_error_sign');

                        ajax_error_sign.hide();

                        // select menu
                        if ($this != '') {
                            $('#mainmenu').find('ul').children('li').removeClass('active');
                            $this.parents('li').addClass('active');
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
                        $('#workspace_wrapper').load(link, function(response, status) {
                            if (status == 'error') {
                                $('#ajax_error_sign').qtip({
                                    content: {
                                        text: response
                                    },
                                    style: {
                                        classes: 'qtip-youtube'
                                    }
                                });

                                $(this).html("<div id='workspace'><div class='container'>" +
                                "<h1 align='center'>" + response + '</h1>' +
                                '</div></div>');

                                ajax_error_sign.show();
                            }
                        });

                        window.history.pushState({path: link}, '', link);

                        ajaxloader.delay(10).hide(10);

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
        }
    };
});