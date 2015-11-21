define(['jquery', 'once'], function ($, once) {
    var methods;

    return methods = {
        loadMenu: function (iqnVal) {
            $('#configureTargetMenu').load('/phpietadmin/targets/configure/' + iqnVal + '/menu', function (response, status) {
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
                    // Add/Remove active classes here
                    var $configureTargetBodyMenu = $('.configureTargetBodyMenu');
                    $configureTargetBodyMenu.once('click', function() {
                        $configureTargetBodyMenu.removeClass('active').parents().removeClass('active');
                        $(this).addClass('active').parents().addClass('active');
                    });
                }
            });
        },
        loadBody: function (body, iqnVal) {
            $('#configureTargetBody').load('/phpietadmin/targets/configure/' + iqnVal + '/' + body.substring(1), function (response, status) {
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
        },
        initialLoad: function ($targetSelect) {
            var iqn = $targetSelect.find("option:selected"),
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