define(['jquery', 'once'], function ($, once) {
    var methods;

    return methods = {
        addEventHandler: function() {
            $('#targetSelect').once('change', function () {
                methods.initialLoad($(this));
            });
        },
        loadMenu: function (iqnVal) {
            var $configureTargetMenu = $('#configureTargetMenu');

            $configureTargetMenu.fadeOut('fast', function(){
                $configureTargetMenu.load('/phpietadmin/targets/configure/' + iqnVal + '/menu', function (response, status) {
                    $configureTargetMenu.fadeIn('fast');
                    if (status === 'error') {
                        // Display error message
                        $(this).html(
                            "<div class='container'>" +
                            "<div class='alert alert-warning' role='alert'>" +
                            '<h3 align="center">Sorry, can\'t load the menu!</h3>' +
                            '</div>' +
                            '</div>'
                        );
                    } else {
                        // Add/Remove active classes and se event handler for body content
                        var $configureTargetBodyMenu = $('.configureTargetBodyMenu');
                        $configureTargetBodyMenu.once('click', function() {
                            $configureTargetBodyMenu.removeClass('active').parents().removeClass('active');
                            $(this).addClass('active').parents().addClass('active');
                        });

                        $configureTargetBodyMenu.once('click', function () {
                            var url = $(this).children('a').attr('href'),
                                hash = url.substring(url.indexOf('#'));
                            methods.loadBody(hash, iqnVal);
                        });
                    }
                });
            });
        },
        loadBody: function (body, iqnVal) {
            var $configureTargetBody = $('#configureTargetBody');

            $configureTargetBody.fadeOut('fast', function(){
                $configureTargetBody.load('/phpietadmin/targets/configure/' + iqnVal + '/' + body.substring(1), function (response, status) {
                    $configureTargetBody.fadeIn('fast');
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
        },
        initialLoad: function ($targetSelect) {
            var iqn = $('#targetSelect').find("option:selected"),
                iqnVal = iqn.val(),
                hash = window.location.hash,
                link;

            if (iqn.attr('id') === 'default') {
                link = '/phpietadmin/targets/configure';
                $('#configureTargetMenu').html('');
                $('#configureTargetBody').html('');
            } else {
                // Always load the maplun menu
                if (hash === '') {
                    hash = '#maplun';
                }

                methods.loadMenu(iqnVal);
                methods.loadBody(hash, iqnVal);
                link = '/phpietadmin/targets/configure/' + iqnVal + hash;
            }

            window.history.pushState({path: link}, '', link);
        }
    };
});